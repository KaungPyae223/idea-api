<?php

namespace Database\Factories;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $idea = Idea::factory()->create();
        $user = User::factory()->create();


        return [
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment',
            'is_anonymous' => false
        ];
    }
}
