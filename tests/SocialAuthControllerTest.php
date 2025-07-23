<?php

namespace Tests\App;

use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;
use Orchestra\Testbench\TestCase;
use Facades\Rzb\SocialAuth\SocialAuth;
use Rzb\SocialAuth\SocialAuthServiceProvider;
use Tests\Mocks\SocialProvider;
use Tests\Models\User as Sociable;

class SocialAuthControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Socialite::shouldReceive('driver')->andReturn(new SocialProvider());

        Config::set('socialauth.sociables.user.model', Sociable::class);
    }

    /** @test */
    public function it_returns_the_url_from_the_given_provider()
    {
        SocialAuth::shouldReceive('stateless->getRedirectUrl')
            ->andReturn('https://provider.example');

        $response = $this->getJson(route('social.redirect', [
            'provider' => 'google',
            'sociable' => 'user',
        ]));

        $response
            ->assertOk()
            ->assertSee('https://provider.example');
    }

    /** @test */
    public function it_returns_the_sociable_model_for_the_given_provider_and_token()
    {
        $sociable = Sociable::factory()->make();
        SocialAuth::shouldReceive('stateless->user')
            ->andReturn($sociable);

        $response = $this->postJson(route('social.callback', [
            'provider' => 'google',
            'sociable' => 'user',
            'access_token' => 'whatever',
            'code' => 'whatever',
        ]));

        $response
            ->assertOk()
            ->assertJson([
                'data' => $sociable->only([
                    'first_name',
                    'last_name',
                    'email',
                ]),
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
