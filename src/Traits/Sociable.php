<?php

namespace Rzb\SocialAuth\Traits;

use Laravel\Socialite\Contracts\User as SocialUser;
use Rzb\SocialAuth\Models\SocialAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait Sociable
{
    public static function createFromSocialUser(SocialUser $socialUser): self
    {
        $name = Str::of($socialUser->getName());

        return self::forceCreate([
            'email' => $socialUser->getEmail(),
            'first_name' => $name->before(' '),
            'last_name' => $name->after(' '),
            'password' => Hash::make(Str::random(10)),
        ]);
    }

    public function socialAccounts()
    {
        return $this->morphMany(SocialAccount::class, 'sociable');
    }
}
