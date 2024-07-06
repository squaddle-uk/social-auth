<?php

namespace Rzb\SocialAuth;

use App\Exceptions\SocialAuthException;
use InvalidArgumentException;
use Rzb\SocialAuth\Contracts\Sociable;
use Rzb\SocialAuth\Models\SocialAccount;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

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

        return $this->providerUserToAppUser($providerUser);
    }

    private function providerUserToAppUser($providerUser): Sociable
    {
        $socialAccount = SocialAccount::with('sociable')->firstOrNew([
            'provider_user_id' => $providerUser->getId(),
            'provider'         => $this->getProviderName(),
        ]);

        // If we found a matching Social Account, we can safely assume that we already have
        // a corresponding User, i.e. the User has already signed in using this Social
        // account in the past, as Social Accounts cannot be created without Users.
        if ($socialAccount->exists) {
            return $socialAccount->sociable;
        }

        $user = $this->getOrCreateUser($providerUser);

        $socialAccount->sociable()->associate($user)->save();

        return $user;
    }

    private function getOrCreateUser($providerUser): Sociable
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
