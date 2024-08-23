<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_is_stored_successfully(): void
    {
        $post = Post::factory()->create();
        $data = [
            'content' => fake()->paragraph()
        ];
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/api/posts/' . $post->id . '/comments', $data);

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

    public function test_comment_validation_throws_error(): void
    {
        $post = Post::factory()->create();
        $data = [
            'content' => '1'
        ];
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/api/posts/' . $post->id . '/comments', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('content');
    }

    public function test_comment_store_throws_authentication_error(): void
    {
        $post = Post::factory()->create();
        $data = [
            'content' => fake()->paragraph()
        ];

        $response = $this->postJson('/api/posts/' . $post->id . '/comments', $data);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
