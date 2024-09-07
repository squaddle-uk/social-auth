<?php

namespace Rzb\SocialAuth\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Rzb\SocialAuth\Http\Requests\SocialAuthCallbackRequest as CallbackRequest;
use Rzb\SocialAuth\SocialAuth;

class SocialAuthController extends Controller
{
    public function __construct(protected SocialAuth $socialAuth)
    {}

    public function redirect(): string
    {
        return $this->socialAuth->stateless()->getRedirectUrl();
    }

    public function callback(CallbackRequest $request): JsonResource
    {
        return $this->socialAuth->stateless()->user()->toResource();
    }
}
