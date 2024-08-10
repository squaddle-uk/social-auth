<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthUser;
use Rzb\SocialAuth\Contracts\Resourceable as ResourceableContract;
use Rzb\SocialAuth\Contracts\Sociable as SociableContract;
use Rzb\SocialAuth\Database\Factories\UserFactory;
use Rzb\SocialAuth\Models\SocialAccount;
use Rzb\SocialAuth\Traits\Resourceable;
use Rzb\SocialAuth\Traits\Sociable;

class User extends AuthUser implements SociableContract, ResourceableContract
{
    use HasFactory;
    use Resourceable;
    use Sociable;

    protected $guarded = [];

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
