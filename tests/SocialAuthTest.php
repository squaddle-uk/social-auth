<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Rzb\SocialAuth\Models\SocialAccount;
use Rzb\SocialAuth\SocialAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;
use Rzb\SocialAuth\SocialAuthServiceProvider;
use Tests\Mocks\SocialProvider;
use Orchestra\Testbench\TestCase;
use Tests\Models\User as Sociable;

class SocialAuthTest extends TestCase
{
    use RefreshDatabase;

    private SocialAuth $socialAuth;

    public function setUp(): void
    {
        parent::setUp();

        Socialite::shouldReceive('driver')->andReturn(new SocialProvider());

        Config::set('socialauth.sociables.user.model', Sociable::class);

        $this->socialAuth = new SocialAuth('google', 'user');
    }

    /** @test */
    public function it_creates_user_and_social_account_when_email_is_not_found()
    {
        $this->socialAuth->getUserFromToken('whatever');

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('social_accounts', 1);
        $this->assertDatabaseHas('social_accounts', [
            'sociable_id' => Sociable::first()->id,
            'sociable_type' => Sociable::class,
        ]);
    }

    /** @test */
    public function it_creates_social_account_and_associates_it_with_existing_user_when_email_is_found()
    {
        $user = Sociable::factory()->create(['email' => 'sociallogin@example.com']);

        $this->socialAuth->getUserFromToken('whatever');

        $this->assertDatabaseHas('users', ['email' => 'sociallogin@example.com']);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('social_accounts', ['sociable_id' => $user->id]);
        $this->assertDatabaseCount('social_accounts', 1);
    }

    /** @test */
    public function it_does_not_create_user_nor_social_account_when_token_is_found()
    {
        SocialAccount::factory()
            ->for(Sociable::factory()->create(['email' => 'sociallogin@example.com']), 'sociable')
            ->create(['provider_user_id' => 'a_google_token', 'provider' => 'social']);

        $this->socialAuth->getUserFromToken('whatever');

        $this->assertDatabaseHas('users', ['email' => 'sociallogin@example.com']);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('social_accounts', 1);
    }

    protected function getPackageProviders($app)
    {
        return [
            SocialAuthServiceProvider::class,
            SocialiteServiceProvider::class,
        ];
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}
