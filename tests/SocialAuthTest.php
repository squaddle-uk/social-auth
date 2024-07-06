<?php

namespace Tests\Helpers;

use App\Helpers\SocialAuth;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\Mocks\SocialProvider;
use Orchestra\Testbench\TestCase;

class SocialAuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Socialite::shouldReceive('driver')->andReturn(new SocialProvider());
    }

    /** @test */
    public function it_creates_user_and_social_account_when_email_is_not_found()
    {
        SocialAuth::provider('google')
            ->stateless()
            ->getUserFromToken('whatever');

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('social_accounts', 1);
    }

    /** @test */
    public function it_creates_social_account_and_associates_it_with_existing_user_when_email_is_found()
    {
        $user = User::factory()->create(['email' => 'sociallogin@example.com']);

        SocialAuth::provider('google')
            ->stateless()
            ->getUserFromToken('whatever');

        $this->assertDatabaseHas('users', ['email' => 'sociallogin@example.com']);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('social_accounts', ['user_id' => $user->id]);
        $this->assertDatabaseCount('social_accounts', 1);
    }

    /** @test */
    public function it_does_not_create_user_nor_social_account_when_token_is_found()
    {
        User::factory()
            ->hasSocialAccounts(['provider_user_id' => 'a_google_token', 'provider' => 'social'])
            ->create(['email' => 'sociallogin@example.com']);

        SocialAuth::provider('google')
            ->stateless()
            ->getUserFromToken('whatever');

        $this->assertDatabaseHas('users', ['email' => 'sociallogin@example.com']);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('social_accounts', 1);
    }
}
