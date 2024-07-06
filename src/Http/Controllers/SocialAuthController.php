<?php

namespace R64\SocialAuth\Http\Controllers;

//use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use R64\SocialAuth\Facades\SocialAuth;
use R64\SocialAuth\Http\Requests\SocialAuthCallbackRequest;

class SocialAuthController extends Controller
{
    public function redirect(string $provider, string $sociable): string
    {
        return SocialAuth::provider($provider)
            ->for($sociable)
            ->stateless()
            ->getRedirectUrl();
    }

    public function callback(
        string $provider,
        string $sociable,
        SocialAuthCallbackRequest $request): JsonResponse
    {
        $user = SocialAuth::provider($provider)
            ->for($sociable)
            ->stateless()
            ->getUserFromToken($request->access_token);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => '',
        ], 200);
    }
}
