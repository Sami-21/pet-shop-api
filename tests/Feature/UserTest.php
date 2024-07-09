<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected JwtService $jwtService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email' => 'example@test.com']);
        $this->jwtService = $this->app->make(JwtService::class);
    }

    public function test_login_successful(): void
    {
        $credentials = ['email' => 'example@test.com', 'password' => 'password'];
        $response = $this->postJson('/api/v1/user/login', $credentials);

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => ['token'],
            'error',
            'errors',
            'extra',
        ]);
    }

    public function test_login_failed(): void
    {
        $credentials = ['email' => 'example@test.com', 'password' => Str::random(8)];
        $response = $this->postJson('/api/v1/user/login', $credentials);

        $response->assertJsonStructure([
            'success',
            'data',
            'error',
            'errors',
            'extra',
        ]);
    }

    public function test_login_no_account_found(): void
    {
        $credentials = ['email' => 'other.example@test.com', 'password' => 'password'];
        $response = $this->postJson('/api/v1/user/login', $credentials);

        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors',
        ]);
    }

    public function test_login_invalid_credentials(): void
    {
        $credentials = ['email' => 'example@test.com', 'password' => '12345678'];
        $response = $this->postJson('/api/v1/user/login', $credentials);

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'data',
            'error',
            'errors',
            'extra',
        ])->assertjson([
            'success' => 0,
            'data' => [],
            'error' => 'Failed to authenticate user, check your credentials',
            'errors' => [],
            'extra' => [],
        ]);
    }

    public function test_create_user_account_success(): void
    {
        $userData = [
            'first_name' => 'eu adipisicing esse',
            'last_name' => 'pariatur culpa reprehenderit cupidatat ea',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'veniam mollit commodo',
            'phone_number' => '+123456789',
        ];
        $response = $this->postJson('/api/v1/user/create', $userData);

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => ['token'],
            'error',
            'errors',
            'extra',
        ]);
    }

    public function test_create_user_account_unprocessable_content(): void
    {
        $userData = [
            'first_name' => 'eu adipisicing esse',
            'last_name' => 'pariatur culpa reprehenderit cupidatat ea',
            'email' => 'test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => 'veniam mollit commodo',
            'phone_number' => '+123456789',
        ];
        $response = $this->postJson('/api/v1/user/create', $userData);

        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors',
        ]);
    }

    public function test_get_user_orders(): void
    {
        $token = $this->jwtService->generateToken('uuid', $this->user->uuid);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->getJson('/api/v1/user/orders');

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => ['orders'],
            'error',
            'errors',
            'extra',
        ]);
    }
}
