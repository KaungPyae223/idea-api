<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Idea;
use App\Models\Comment;
use App\Models\Permission;
use App\Repositories\ReportRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $reportRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reportRepository = new ReportRepository();

        $this->actingAs(User::factory()->create());
    }

    public function test_hide_idea()
    {
        $idea = Idea::factory()->create(['hidden' => false]);

        $message = $this->reportRepository->hideIdea($idea->id, true);

        $this->assertEquals("Successfully hide the idea id {$idea->id}", $message);
        $this->assertDatabaseHas('ideas', ['id' => $idea->id, 'hidden' => true]);
    }

    public function test_unhide_idea()
    {
        $idea = Idea::factory()->create(['hidden' => true]);

        $message = $this->reportRepository->hideIdea($idea->id, false);

        $this->assertEquals("Successfully un hide the idea id {$idea->id}", $message);
        $this->assertDatabaseHas('ideas', ['id' => $idea->id, 'hidden' => false]);
    }

    public function test_hide_all_user_posts()
    {
        $user = User::factory()->create(['hidden' => false]);
        $ideas = Idea::factory()->count(2)->create(['user_id' => $user->id, 'hidden' => false]);
        $comments = Comment::factory()->count(2)->create(['user_id' => $user->id, 'hidden' => false]);

        $message = $this->reportRepository->hideAllUserPosts($user->id, true);

        $this->assertEquals("Successfully Hide the user id {$user->id}'s idea and comments", $message);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'hidden' => true]);
        foreach ($ideas as $idea) {
            $this->assertDatabaseHas('ideas', ['id' => $idea->id, 'hidden' => true]);
        }
        foreach ($comments as $comment) {
            $this->assertDatabaseHas('comments', ['id' => $comment->id, 'hidden' => true]);
        }
    }

    public function test_ban_user_permissions()
    {
        $user = User::factory()->create();
        $user->permissions()->attach([12, 13]);

        $message = $this->reportRepository->banAndAccess($user->id, true);

        $this->assertEquals("successfully ban post idea and comment permissions to the user id {$user->id}", $message);
        $this->assertDatabaseMissing('user_permissions', ['user_id' => $user->id, 'permission_id' => 12]);
        $this->assertDatabaseMissing('user_permissions', ['user_id' => $user->id, 'permission_id' => 13]);
    }

    public function test_restore_user_permissions()
    {
        $user = User::factory()->create();

        $message = $this->reportRepository->banAndAccess($user->id, false);

        $this->assertEquals("successfully access post idea and comment permissions to the user id {$user->id}", $message);
        $this->assertDatabaseHas('user_permissions', ['user_id' => $user->id, 'permission_id' => 12]);
        $this->assertDatabaseHas('user_permissions', ['user_id' => $user->id, 'permission_id' => 13]);
    }
}
