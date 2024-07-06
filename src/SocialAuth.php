<?php

namespace Rzb\SocialAuth;

use Rzb\Exceptions\SocialAuthException;
use Rzb\SocialAuth\Contracts\Sociable;
use Rzb\SocialAuth\Models\SocialAccount;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SocialAuth
{
    private Provider $provider;

    private string $sociable;

    public function __construct(string $provider, string $sociable)
    {
        $config = config("socialauth.sociables.$sociable");

        if (! $config || ! array_key_exists('model', $config)) {
            throw new InvalidArgumentException(
                "SocialAuth sociable [$sociable] is not defined."
            );
        }

        $this->sociable = $config['model'];

        if (! array_key_exists('providers', $config) || ! in_array($provider, $config['providers'])) {
            throw new InvalidArgumentException(
                "SocialAuth provider [$provider] isn't allowed for sociable [$sociable]."
            );
        }

        $this->provider = Socialite::driver($provider);
    }

    public function stateless(): self
    {
        $this->provider->stateless();

        return $this;
    }

    public function getRedirectUrl(): string
    {
        return $this->provider->redirect()->getTargetUrl();
    }

    /**
     * @throws SocialAuthException
     */
    public function getUserFromToken(string $token): Sociable
    {
        try {
            $providerUser = $this->provider->userFromToken($token);
        } catch (\Exception $e) {
            throw new SocialAuthException($this->getProviderName());
        }

        return $this->providerUserToSociable($providerUser);
    }

    private function providerUserToSociable($providerUser): Sociable
    {
        $socialAccount = SocialAccount::with('sociable')->firstOrNew([
            'provider_user_id' => $providerUser->getId(),
            'provider'         => $this->getProviderName(),
        ]);

        // If we found a matching Social Account, we can safely assume that we already have
        // a corresponding Sociable, i.e. the Sociable has already signed in using this
        // Social Account, as Social Accounts cannot be created without a Sociable.
        if ($socialAccount->exists) {
            return $socialAccount->sociable;
        }

        $sociable = $this->getOrCreateSociable($providerUser);

        $socialAccount->sociable()->associate($sociable)->save();

        return $sociable;
    }

    private function getOrCreateSociable($providerUser): Sociable
    {
        return call_user_func(
            [$this->sociable, 'createFromSocialUser'],
            $providerUser
        );
    }

    private function getProviderName(): string
    {
        return Str::of(class_basename($this->provider))->lower()->remove('provider');
    }
}
