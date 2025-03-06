<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SystemSetting>
 */
class SystemSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idea_closure_date' => fake()->date(),
            'final_closure_date' => fake()->date(),
            'academic_year' => fake()->date(),
            'status' => fake()->boolean()
        ];
    }
}
