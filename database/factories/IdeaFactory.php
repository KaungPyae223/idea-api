<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Idea>
 */
class IdeaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $systemSetting = SystemSetting::factory()->create();
        $user = User::factory()->create();

        return [
            "user_id" => $user->id,
            "title" => fake()->name(),
            "content" => fake()->name(),
            "system_setting_id" => $systemSetting->id,
            "is_anonymous" => fake()->boolean(),
            "is_enabled" => fake()->boolean(),
        ];
    }
}
