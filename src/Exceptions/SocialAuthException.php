<?php

namespace Rzb\Exceptions;

use Exception;

class SocialAuthException extends Exception
{
    public function __construct(string $provider)
    {
        parent::__construct();

        $this->message = "Social authentication using [$provider] not authorized.";
    }
}
