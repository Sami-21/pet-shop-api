<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use App\Models\User;
use App\Services\JwtService;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Str;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected JwtService $jwtService;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jwtService = $this->app->make(JwtService::class);
        $this->user = User::factory()->create(['email' => 'admin@buckhill.co.uk', 'password' => Hash::make('admin'), 'is_admin' => true]);
        $this->product = Product::factory()->create([]);
    }


    public function test_get_products(): void
    {
        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);
    }

    public function test_get_product(): void
    {
        $response = $this->getJson('/api/v1/product/' . $this->product->uuid);

        $response->assertStatus(200)->assertJsonStructure([
            "id",
            "uuid",
            "category_uuid",
            "title",
            "price",
            "metadata",
            "description",
            "created_at",
            "updated_at",
        ]);
    }

    public function test_get_product_not_found(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->getJson('/api/v1/product/' . Str::uuid());

        $response->assertStatus(404);
    }

    public function test_store_product(): void
    {
        $category = Category::factory()->create();
        $file = File::factory()->create();
        $brand = Brand::factory()->create();
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/v1/product/create', [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'message',
            'product' => [
                "id",
                "uuid",
                "category_uuid",
                "title",
                "price",
                "metadata",
                "description",
                "created_at",
                "updated_at",
            ]
        ]);

        $this->assertDatabaseHas('products', [
            'category_uuid' => $category->uuid,
        ]);
    }

    public function test_store_product_unauthenticated(): void
    {
        $category = Category::factory()->create();
        $file = File::factory()->create();
        $brand = Brand::factory()->create();
        $response = $this->postJson('/api/v1/product/create', [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid
        ]);


        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated'
        ]);
    }

    public function test_store_product_unauthorized(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        $category = Category::factory()->create();
        $file = File::factory()->create();
        $brand = Brand::factory()->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/v1/product/create', [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid
        ]);

        $response->assertStatus(403)->assertJson([
            'error' => 'Unauthorized'
        ]);
    }

    public function test_update_product(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $category = Category::factory()->create();
        $file = File::factory()->create();
        $brand = Brand::factory()->create();
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->putJson('/api/v1/product/' . $this->product->uuid, [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'message',
            'product' => [
                "id",
                "uuid",
                "category_uuid",
                "title",
                "price",
                "metadata",
                "description",
                "created_at",
                "updated_at",
            ]
        ]);

        $this->assertDatabaseHas('products', [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid
        ]);
    }

    public function test_update_product_not_found(): void
    {
        $category = Category::factory()->create();
        $file = File::factory()->create();
        $brand = Brand::factory()->create();
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->putJson('/api/v1/product/' . Str::uuid(), [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid
        ]);

        $response->assertStatus(404);
    }

    public function test_update_product_unauthenticated(): void
    {
        $category = Category::factory()->create();
        $file = File::factory()->create();
        $brand = Brand::factory()->create();
        $response = $this->put('/api/v1/product/' . $this->product->uuid, [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid
        ]);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated'
        ]);
    }

    public function test_update_product_unauthorized(): void
    {

        $user = User::factory()->create(['is_admin' => false]);
        $category = Category::factory()->create();
        $file = File::factory()->create();
        $brand = Brand::factory()->create();
        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->putJson('/api/v1/product/' . $this->product->uuid, [
            'category_uuid' => $category->uuid,
            'title' => 'test product title',
            'price' => 73433.56,
            'description' => 'eu dolor sed',
            'image' => $file->uuid,
            'brand' => $brand->uuid
        ]);

        $response->assertStatus(403)->assertJson([
            'error' => 'Unauthorized'
        ]);
    }

    public function test_delete_product(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->deleteJson('/api/v1/product/' . $this->product->uuid);

        $response->assertStatus(200)->assertJsonStructure([
            'message',
        ]);
        $this->assertSoftDeleted('products', [
            'uuid' => $this->product->uuid
        ]);
    }

    public function test_delete_product_not_found(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->deleteJson('/api/v1/product/' . Str::uuid());

        $response->assertStatus(404);
    }

    public function test_delete_product_unauthenticated(): void
    {
        $response = $this->putJson('/api/v1/product/' . $this->product->uuid);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated'
        ]);
    }

    public function test_delete_product_unauthorized(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->putJson('/api/v1/product/' . $this->product->uuid);

        $response->assertStatus(403)->assertJson([
            'error' => 'Unauthorized'
        ]);
    }
}
