<?php

namespace Tests;

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;
use Orchestra\Testbench\TestCase;
use R64\SocialAuth\Facades\SocialAuth;
use R64\SocialAuth\SocialAuthServiceProvider;
use Tests\Mocks\SocialProvider;
use Tests\Models\User;

class SocialAuthControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Socialite::shouldReceive('driver')->andReturn(new SocialProvider());
    }

    /** @test */
    public function it_returns_the_url_from_the_given_provider()
    {
        SocialAuth::shouldReceive('provider->for->stateless->getRedirectUrl')
            ->andReturn('https://provider.example');

        $response = $this->getJson(route('social.redirect', [
            'provider' => 'google',
            'sociable' => User::class,
        ]));

        $response
            ->assertOk()
            ->assertSee('https://provider.example');
    }

    /** @test */
    public function it_returns_the_sociable_model_for_the_given_provider_and_token()
    {
        $sociable = User::factory()->make();
        SocialAuth::shouldReceive('provider->for->stateless->getUserFromToken')
            ->andReturn($sociable);

        $response = $this->postJson(route('social.callback', [
            'provider' => 'google',
            'sociable' => User::class,
            'access_token' => 'whatever',
        ]));

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => '',
                'data' => $sociable->toArray(),
            ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            SocialAuthServiceProvider::class,
            SocialiteServiceProvider::class,
        ];
    }
}
