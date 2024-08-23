<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_are_returned_correctly(): void
    {
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
                        'user',
                        'created_at',
                        'comments',
                        'likes_count',
                        'is_liked',
                    ]
                ]
            ]);
    }

    public function test_post_is_stored_successfully(): void
    {
        $data = [
            'content' => fake()->paragraph()
        ];
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => [
                'id',
                'content',
                'user',
                'created_at',
                'comments',
                'likes_count',
                'is_liked',
            ]]);
    }

    public function test_post_validation_throws_error(): void
    {
        $data = [
            'content' => '1'
        ];
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('content');
    }

    public function test_post_store_throws_authentication_error(): void
    {
        $data = [
            'content' => fake()->paragraph()
        ];

        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_post_is_deleted_successfully(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
        $this->actingAs($user);

        $response = $this->deleteJson('/api/posts/' . $post->id);

        $response->assertStatus(204);
    }

    public function test_unauthorized_post_deletion_throws_error(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for(User::factory())->create();
        $this->actingAs($user);

        $response = $this->deleteJson('/api/posts/' . $post->id);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_post_deletion_throws_error(): void
    {
        $post = Post::factory()->for(User::factory())->create();

        $response = $this->deleteJson('/api/posts/' . $post->id);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
