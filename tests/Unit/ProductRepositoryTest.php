<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = new ProductRepository();
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $file = File::factory()->create();
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $data = [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid,
        ];

        $product = $this->productRepository->storeProduct($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }

    /** @test */
    public function it_can_find_a_product()
    {
        $product = Product::factory()->create();

        $foundProduct = $this->productRepository->getProduct($product->uuid);

        $this->assertInstanceOf(Product::class, $foundProduct);
        $this->assertEquals($product->id, $foundProduct->id);
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $product = Product::factory()->create();
        $file = File::factory()->create();
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $updatedData = [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid,
        ];

        $this->productRepository->updateProduct($product, $updatedData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $this->productRepository->deleteProduct($product);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
