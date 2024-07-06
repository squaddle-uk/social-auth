<?php

namespace Rzb\SocialAuth\Contracts;

use Laravel\Socialite\Contracts\User as SocialUser;

interface Sociable
{
    public static function createFromSocialUser(SocialUser $socialUser): self;
}
