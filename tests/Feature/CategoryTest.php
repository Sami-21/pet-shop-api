<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Services\JwtService;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Category $category;

    protected JwtService $jwtService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jwtService = $this->app->make(JwtService::class);
        $this->user = User::factory()->create(['email' => 'admin@buckhill.co.uk', 'password' => Hash::make('admin'), 'is_admin' => true]);
        $this->category = Category::factory()->create();
    }

    public function test_get_categories(): void
    {
        $response = $this->getJson('/api/v1/categories');

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
            'total',
        ]);
    }

    public function test_get_category(): void
    {
        $response = $this->getJson('/api/v1/category/'.$this->category->uuid);

        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'uuid',
            'title',
            'slug',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_get_category_not_found(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/v1/category/'.Str::uuid());

        $response->assertStatus(404);
    }

    public function test_store_category(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/v1/category/create', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'message',
            'category' => [
                'id',
                'uuid',
                'title',
                'slug',
                'created_at',
                'updated_at',
            ],
        ]);
        $this->assertDatabaseHas('categories', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);
    }

    public function test_store_category_unauthenticated(): void
    {
        $response = $this->postJson('/api/v1/category/create', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated',
        ]);
    }

    public function test_store_category_unauthorized(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/v1/category/create', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(403)->assertJson([
            'error' => 'Unauthorized',
        ]);
    }

    public function test_update_category(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->putJson('/api/v1/category/'.$this->category->uuid, [
            'title' => 'Updated Test title',
            'slug' => 'Updated Test slug',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'message',
            'category' => [
                'id',
                'uuid',
                'title',
                'slug',
                'created_at',
                'updated_at',
            ],
        ]);
        $this->assertDatabaseHas('categories', [
            'title' => 'Updated Test title',
            'slug' => 'Updated Test slug',
        ]);
    }

    public function test_update_category_not_found(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->putJson('/api/v1/category/'.Str::uuid(), [
            'title' => 'Updated Test title',
            'slug' => 'Updated Test slug',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_category_unauthenticated(): void
    {
        $response = $this->putJson('/api/v1/category/'.$this->category->uuid, [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated',
        ]);
    }

    public function test_update_category_unauthorized(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->putJson('/api/v1/category/'.$this->category->uuid, [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(403)->assertJson([
            'error' => 'Unauthorized',
        ]);
    }

    public function test_delete_category(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->deleteJson('/api/v1/category/'.$this->category->uuid);

        $response->assertStatus(200)->assertJsonStructure([
            'message',
        ]);
        $this->assertDatabaseMissing('categories', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);
    }

    public function test_delete_category_not_found(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->deleteJson('/api/v1/category/'.Str::uuid());

        $response->assertStatus(404);
    }

    public function test_delete_category_unauthenticated(): void
    {
        $response = $this->putJson('/api/v1/category/'.$this->category->uuid);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated',
        ]);
    }

    public function test_delete_category_unauthorized(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->putJson('/api/v1/category/'.$this->category->uuid);

        $response->assertStatus(403)->assertJson([
            'error' => 'Unauthorized',
        ]);
    }
}
