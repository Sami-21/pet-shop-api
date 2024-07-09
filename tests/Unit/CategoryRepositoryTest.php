<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    /** @test */
    public function it_can_create_a_category()
    {
        $data = [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ];

        $category = $this->categoryRepository->storeCategory($data);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertDatabaseHas('categories', $data);
    }

    /** @test */
    public function it_can_find_a_category()
    {
        $category = Category::factory()->create();

        $foundCategory = $this->categoryRepository->getCategory($category->uuid);

        $this->assertInstanceOf(Category::class, $foundCategory);
        $this->assertEquals($category->id, $foundCategory->id);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $category = Category::factory()->create();

        $updatedData = [
            'title' => 'Updated Test title',
            'slug' => 'Updated Test slug',
        ];

        $this->categoryRepository->updateCategory($category, $updatedData);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertDatabaseHas('categories', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $category = Category::factory()->create();

        $this->categoryRepository->deleteCategory($category);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
