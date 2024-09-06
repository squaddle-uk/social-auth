<?php

namespace Rzb\SocialAuth\Exceptions;

use Exception;

class SocialAuthException extends Exception
{
    public function __construct(string $provider, string $token)
    {
        parent::__construct();

        $this->message = "Social authentication using [$provider] with token [$token] not authorized.";
    }
}
