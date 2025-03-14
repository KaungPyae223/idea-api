<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = new CategoryRepository();
    }

    public function test_find_category()
    {

        $category = Category::factory()->create(["name"=>"test_category"]);
        $foundCategory = $this->categoryRepository->find($category->id);

        $this->assertNotNull($foundCategory);
        $this->assertEquals($category->id, $foundCategory->id);
    }

    public function test_create_category()
    {

        $data = ['name' => 'Test Category'];
        $category = $this->categoryRepository->create($data);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_update_category()
    {

        $category = Category::factory()->create(['name' => 'Old Name']);
        $updatedCategory = $this->categoryRepository->update($category->id, ['name' => 'New Name']);

        $this->assertEquals('New Name', $updatedCategory->fresh()->name);
    }

    public function test_destroy_category()
    {
        $category = Category::factory()->create();

        $result = $this->categoryRepository->destroy($category->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
