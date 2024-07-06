<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Socialite\Contracts\User as SocialUser;
use Illuminate\Foundation\Auth\User as AuthUser;
use Rzb\SocialAuth\Contracts\Sociable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Rzb\SocialAuth\Database\Factories\UserFactory;
use Rzb\SocialAuth\Models\SocialAccount;

class User extends AuthUser implements Sociable
{
    use HasFactory;

    protected $guarded = [];

    public static function createFromSocialUser(SocialUser $socialUser): Sociable
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

    public function socialAccounts()
    {
        return $this->morphMany(SocialAccount::class, 'sociable');
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
