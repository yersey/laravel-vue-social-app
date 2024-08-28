<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_registered_successfully(): void
    {
        $data = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => fake()->password(),
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'created_at',
                ]
            ]);
    }

    public function test_user_validation_throws_error(): void
    {
        $data = [
            'name' => fake()->name(),
            'email' => 'test@test.',
            'password' => fake()->password(),
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('email');
    }

    public function test_token_is_issued(): void
    {
        $user = User::factory([
            'password' => 'testtest'
        ])->create();
        $data = [
            'email' => $user->email,
            'password' => 'testtest'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_old_token_on_login_is_deleted(): void
    {
        $user = User::factory([
            'password' => 'testtest'
        ])->create();
        $data = [
            'email' => $user->email,
            'password' => 'testtest'
        ];

        $this->postJson('/api/login', $data);
        $token = PersonalAccessToken::first();
        $this->postJson('/api/login', $data);

        $this->assertModelMissing($token);
    }

    public function test_user_login_with_bad_credentials_throws_error(): void
    {
        $user = User::factory([
            'password' => 'testtest'
        ])->create();
        $data = [
            'email' => $user->email,
            'password' => 'testtest123'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Bad credentials']);
    }

    public function test_user_user_logout_throws_authentication_error(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_token_is_deleted_successfully(): void
    {
        $user = User::factory([
            'password' => 'testtest'
        ])->create();
        $data = [
            'email' => $user->email,
            'password' => 'testtest'
        ];

        $this->postJson('/api/login', $data);
        $this->postJson('/api/logout', $data);

        $this->assertEquals(PersonalAccessToken::count(), 0);
    }
}
