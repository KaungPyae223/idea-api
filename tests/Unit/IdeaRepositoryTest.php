<?php

namespace Tests\Unit;

use App\Models\Idea;
use App\Models\Category;
use App\Models\File;
use App\Models\SystemSetting;
use App\Models\User;
use App\Repositories\IdeaRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdeaRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $ideaRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ideaRepository = new IdeaRepository();
    }

    public function test_find_idea()
    {
        $idea = Idea::factory()->create(['title' => 'Test Idea']);

        $foundIdea = $this->ideaRepository->find($idea->id);

        $this->assertNotNull($foundIdea);
        $this->assertEquals('Test Idea', $foundIdea->title);
    }

    public function test_create_idea()
    {


        $category = Category::factory()->create();
        $systemSetting = SystemSetting::factory()->create();
        $user = User::factory()->create();

        $data = [
            "user_id" => $user->id,
            "title" => "New Idea",
            "content" => fake()->name(),
            "system_setting_id" => $systemSetting->id,
            "is_anonymous" => true,
            "is_enabled" => true,
            "category" => $category->id
        ];

        $idea = $this->ideaRepository->create($data);

        $this->assertInstanceOf(Idea::class, $idea);
        $this->assertDatabaseHas('ideas', ['title' => 'New Idea']);
        $this->assertTrue($idea->categories()->exists());
    }

    public function test_update_idea()
    {

        $idea = Idea::factory()->create(['title' => 'Old Title']);
        $category = Category::factory()->create();

        $updatedData = [
            'title' => 'Updated Title',
            'category' => $category->id
        ];

        $updatedIdea = $this->ideaRepository->update($idea->id, $updatedData);


        $this->assertEquals('Updated Title', $updatedIdea->fresh()->title);
        $this->assertDatabaseHas('ideas', ['title' => 'Updated Title']);
    }

    public function test_update_idea_category()
    {

        $idea = Idea::factory()->create();
        $category = Category::factory()->create();

        $data = ['category' => $category->id];

        $this->ideaRepository->updateIdeaCategory($idea->id, $data);

        $this->assertDatabaseHas('category_ideas', [
            'idea_id' => $idea->id,
            'category_id' => $category->id,
        ]);

    }

    public function test_submit_idea()
    {

        $idea = Idea::factory()->create(['is_enabled' => false]);

        $updatedIdea = $this->ideaRepository->submitIdea($idea->id, ['is_enabled' => true]);


        $this->assertTrue($updatedIdea->is_enabled);
    }

    public function test_destroy_idea()
    {

        $idea = Idea::factory()->create();
        $file = File::factory()->create(['idea_id' => $idea->id]);

        $this->ideaRepository->destroy($idea->id);

        $this->assertDatabaseMissing('ideas', ['id' => $idea->id]);
        $this->assertDatabaseMissing('files', ['idea_id' => $idea->id]);
    }
}
