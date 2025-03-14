<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use App\Repositories\CommentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $commentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commentRepository = new CommentRepository();
    }

    public function test_create_comment()
    {

        $idea = Idea::factory()->create();
        $user = User::factory()->create();

        $data = [
            'idea_id' => $idea->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment',
            'is_anonymous' => false
        ];

        $comment = $this->commentRepository->create($data);


        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertDatabaseHas('comments', ['comment' => 'This is a test comment']);
    }

    public function test_find_comment()
    {
        $comment = Comment::factory()->create();

        $foundComment = $this->commentRepository->find($comment->id);

        $this->assertNotNull($foundComment);
        $this->assertEquals($comment->id, $foundComment->id);
    }

    public function test_update_comment()
    {
        $comment = Comment::factory()->create();

        $updatedData = ['comment' => 'Updated test comment'];
        $updatedComment = $this->commentRepository->update($comment->id, $updatedData);

        $this->assertEquals('Updated test comment', $updatedComment->fresh()->comment);
        $this->assertDatabaseHas('comments', ['comment' => 'Updated test comment']);
    }

    public function test_destroy_comment()
    {
        $comment = Comment::factory()->create();
        $result = $this->commentRepository->destroy($comment->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
