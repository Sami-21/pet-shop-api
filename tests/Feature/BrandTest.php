<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use App\Services\JwtService;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Brand $brand;

    protected JwtService $jwtService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jwtService = $this->app->make(JwtService::class);
        $this->user = User::factory()->create(['email' => 'admin@buckhill.co.uk', 'password' => Hash::make('admin'), 'is_admin' => true]);
        $this->brand = Brand::factory()->create();
    }

    public function test_get_brands(): void
    {
        $response = $this->getJson('/api/v1/brands');

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

    public function test_get_brand(): void
    {
        $response = $this->getJson('/api/v1/brand/'.$this->brand->uuid);

        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'uuid',
            'title',
            'slug',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_get_brand_not_found(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/v1/brand/'.Str::uuid());

        $response->assertStatus(404);
    }

    public function test_store_brand(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/v1/brand/create', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(201)->assertJsonStructure([
            'message',
            'brand' => [
                'id',
                'uuid',
                'title',
                'slug',
                'created_at',
                'updated_at',
            ],
        ]);
        $this->assertDatabaseHas('brands', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);
    }

    public function test_store_brand_unauthenticated(): void
    {
        $response = $this->postJson('/api/v1/brand/create', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated',
        ]);
    }

    public function test_store_brand_unauthorized(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->postJson('/api/v1/brand/create', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(403)->assertJson([
            'error' => 'Unauthorized',
        ]);
    }

    public function test_update_brand(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->putJson('/api/v1/brand/'.$this->brand->uuid, [
            'title' => 'Updated Test title',
            'slug' => 'Updated Test slug',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'message',
            'brand' => [
                'id',
                'uuid',
                'title',
                'slug',
                'created_at',
                'updated_at',
            ],
        ]);
        $this->assertDatabaseHas('brands', [
            'title' => 'Updated Test title',
            'slug' => 'Updated Test slug',
        ]);
    }

    public function test_update_brand_not_found(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->putJson('/api/v1/brand/'.Str::uuid(), [
            'title' => 'Updated Test title',
            'slug' => 'Updated Test slug',
        ]);

        $response->assertStatus(404);
    }

    public function test_update_brand_unauthenticated(): void
    {
        $response = $this->putJson('/api/v1/brand/'.$this->brand->uuid, [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated',
        ]);
    }

    public function test_update_brand_unauthorized(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->putJson('/api/v1/brand/'.$this->brand->uuid, [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);

        $response->assertStatus(403)->assertJson([
            'error' => 'Unauthorized',
        ]);
    }

    public function test_delete_brand(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->deleteJson('/api/v1/brand/'.$this->brand->uuid);

        $response->assertStatus(200)->assertJsonStructure([
            'message',
        ]);
        $this->assertDatabaseMissing('brands', [
            'title' => 'Test title',
            'slug' => 'Test slug',
        ]);
    }

    public function test_delete_brand_not_found(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->deleteJson('/api/v1/brand/'.Str::uuid());

        $response->assertStatus(404);
    }

    public function test_delete_brand_unauthenticated(): void
    {
        $response = $this->putJson('/api/v1/brand/'.$this->brand->uuid);

        $response->assertStatus(401)->assertJson([
            'error' => 'Unauthenticated',
        ]);
    }

    public function test_delete_brand_unauthorized(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)->putJson('/api/v1/brand/'.$this->brand->uuid);

        $response->assertStatus(403)->assertJson([
            'error' => 'Unauthorized',
        ]);
    }
}
