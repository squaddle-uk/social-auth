<?php

namespace Rzb\SocialAuth\Traits;

use Laravel\Socialite\Contracts\User as SocialUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait Sociable
{
    public static function createFromSocialUser(SocialUser $socialUser): self
    {
        $name = Str::of($socialUser->getName());

        return self::firstOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'first_name' => $name->before(' '),
            'last_name' => $name->after(' '),
            'password' => Hash::make(Str::random(10)),
        ]);
    }
}
