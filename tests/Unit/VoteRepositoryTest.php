<?php

namespace Tests\Unit;

use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\VoteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class VoteRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $voteRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->voteRepository = new VoteRepository();
    }

    public function test_create_vote()
    {

        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $data = [
            'user_id' => $user->id,
            'idea_id' => $idea->id,
            'vote_value' => 1,
        ];

        $vote = $this->voteRepository->create($data);

        $this->assertInstanceOf(Vote::class, $vote);
        $this->assertDatabaseHas('votes', $data);
    }

    public function test_find_vote()
    {
        $vote = Vote::factory()->create();

        $foundVote = $this->voteRepository->find($vote->id);

        $this->assertInstanceOf(Vote::class, $foundVote);
        $this->assertEquals($vote->id, $foundVote->id);
    }

    public function test_update_vote()
    {
        $vote = Vote::factory()->create([
            'vote_value' => 1,
        ]);

        $updatedData = ['vote_value' => -1];
        $updatedVote = $this->voteRepository->update($vote->id, $updatedData);

        $this->assertEquals(-1, $updatedVote->fresh()->vote_value);
        $this->assertDatabaseHas('votes', ['id' => $vote->id, 'vote_value' => -1]);
    }

    public function test_destroy_vote()
    {
        $vote = Vote::factory()->create();

        $deletedVote = $this->voteRepository->destroy($vote->id);

        $this->assertInstanceOf(Vote::class, $deletedVote);
        $this->assertDatabaseMissing('votes', ['id' => $vote->id]);
    }
}
