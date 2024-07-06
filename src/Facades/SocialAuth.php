<?php

namespace Rzb\SocialAuth\Facades;

use Illuminate\Support\Facades\Facade;

class SocialAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'socialauth';
    }
}
