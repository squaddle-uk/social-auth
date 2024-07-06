<?php

namespace Rzb\SocialAuth;

use App\Exceptions\SocialAuthException;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

class SocialAuth
{
    private Provider $provider;

    public function __construct(string $provider)
    {
        $this->provider = Socialite::driver($provider);
    }

    public static function provider(string $provider): self
    {
        return new self($provider);
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
    public function getUserFromToken(string $token): User
    {
        try {
            $providerUser = $this->provider->userFromToken($token);
        } catch (\Exception $e) {
            throw new SocialAuthException($this->getProviderName());
        }

        return $this->providerUserToAppUser($providerUser);
    }

    private function providerUserToAppUser($providerUser): User
    {
        $socialAccount = SocialAccount::with('user')->firstOrNew([
            'provider_user_id' => $providerUser->getId(),
            'provider'         => $this->getProviderName(),
        ]);

        // If we found a matching Social Account, we can safely assume that we already have
        // a corresponding User, i.e. the User has already signed in using this Social
        // account in the past, as Social Accounts cannot be created without Users.
        if ($socialAccount->exists) {
            return $socialAccount->user;
        }

        $user = $this->getOrCreateUser($providerUser);

        $user->socialAccounts()->save($socialAccount);

        return $user;
    }

    private function getOrCreateUser($providerUser): User
    {
        $name = Str::of($providerUser->getName());

        return User::firstOrCreate([
            'email' => $providerUser->getEmail(),
        ], [
            'first_name' => $name->before(' '),
            'last_name' => $name->after(' '),
            'password' => Hash::make(Str::random(10)),
        ]);
    }

    private function getProviderName(): string
    {
        return Str::of(class_basename($this->provider))->lower()->remove('provider');
    }
}
