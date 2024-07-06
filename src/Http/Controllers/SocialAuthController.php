<?php

namespace Rzb\SocialAuth\Http\Controllers;

//use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Rzb\SocialAuth\Http\Requests\SocialAuthCallbackRequest as CallbackRequest;
use Rzb\SocialAuth\SocialAuth;

class SocialAuthController extends Controller
{
    public function __construct(private SocialAuth $socialAuth)
    {}

    public function redirect(): string
    {
        return $this->socialAuth->stateless()->getRedirectUrl();
    }

    public function callback(CallbackRequest $request): JsonResponse
    {
        $user = $this->socialAuth->stateless()->getUserFromToken($request->access_token);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => '',
        ], 200);
    }
}
