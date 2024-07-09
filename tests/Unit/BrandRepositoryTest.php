<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $brandRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->brandRepository = new BrandRepository();
    }

    /** @test */
    public function it_can_create_a_brand()
    {
        $data = [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ];

        $brand = $this->brandRepository->storeBrand($data);

        $this->assertInstanceOf(Brand::class, $brand);
        $this->assertDatabaseHas('brands', $data);
    }

    /** @test */
    public function it_can_find_a_brand()
    {
        $brand = Brand::factory()->create();

        $foundBrand = $this->brandRepository->getBrand($brand->uuid);

        $this->assertInstanceOf(Brand::class, $foundBrand);
        $this->assertEquals($brand->id, $foundBrand->id);
    }

    /** @test */
    public function it_can_update_a_brand()
    {
        $brand = Brand::factory()->create();

        $updatedData = [
            'title' => 'Updated Test title',
            'slug' => 'Updated Test slug',
        ];

        $this->brandRepository->updateBrand($brand, $updatedData);

        $this->assertInstanceOf(Brand::class, $brand);
        $this->assertDatabaseHas('brands', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_brand()
    {
        $brand = Brand::factory()->create();

        $this->brandRepository->deleteBrand($brand);

        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }
}
