<?php

namespace Tests\Mocks;

use Laravel\Socialite\Contracts\Provider;

class SocialProvider implements Provider
{
    public function stateless()
    {
        return $this;
    }

    public function userFromToken()
    {
        return new SocialUser();
    }

    public function redirect()
    {
        // TODO: Implement redirect() method.
    }

    public function user()
    {
        return $this->userFromToken();
    }
}
