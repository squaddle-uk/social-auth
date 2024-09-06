<?php

namespace Rzb\SocialAuth\Exceptions;

use Exception;

class SocialAuthException extends Exception
{
    public function __construct(protected string $provider, protected string $token, Exception $previous = null)
    {
        $message = "Social authentication using [$provider] not authorized.";

        parent::__construct($message, 0, $previous);
    }
}
