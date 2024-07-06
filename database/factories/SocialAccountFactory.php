<?php

namespace Rzb\SocialAuth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialAccount>
 */
class SocialAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => Factory::factoryForModel(User::class),
            'provider_user_id' => fake()->sha1,
            'provider' => fake()->randomElement([
                'GoogleProvider', 'FacebookProvider', 'TwitterProvider',
            ]),
        ];
    }
}
