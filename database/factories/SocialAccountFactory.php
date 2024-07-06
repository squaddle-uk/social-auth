<?php

namespace Rzb\SocialAuth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Rzb\SocialAuth\Models\SocialAccount;
use Tests\Models\User;

class SocialAccountFactory extends Factory
{
    protected $model = SocialAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sociable_id' => User::factory(),
            'sociable_type' => User::class,
            'provider_user_id' => fake()->sha1,
            'provider' => fake()->randomElement([
                'GoogleProvider', 'FacebookProvider', 'TwitterProvider',
            ]),
        ];
    }
}
