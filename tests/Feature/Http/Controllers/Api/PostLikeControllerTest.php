<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostLikeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_like_is_stored_successfully(): void
    {
        $post = Post::factory()->create();
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/posts/' . $post->id . '/likes');

        $response->assertStatus(201);
    }

    public function test_duplicated_like_throws_error(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Like::factory()->for($user)->for($post, 'likeable')->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/v1/posts/' . $post->id . '/likes');

        $response->assertStatus(409)
            ->assertJson(['message' => 'You have already liked this post.']);
    }

    public function test_like_store_throws_authentication_error(): void
    {
        $post = Post::factory()->create();

        $response = $this->postJson('/api/v1/posts/' . $post->id . '/likes');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_like_is_deleted_successfully(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Like::factory()->for($user)->for($post, 'likeable')->create();
        $this->actingAs($user);

        $response = $this->deleteJson('/api/v1/posts/' . $post->id . '/likes');

        $response->assertStatus(204);
    }

    public function test_like_deletion_throws_unauthenticated_error(): void
    {
        $post = Post::factory()->create();
        Like::factory()->for(User::factory())->for($post, 'likeable')->create();

        $response = $this->deleteJson('/api/v1/posts/' . $post->id . '/likes');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_like_deletion_throws_not_found_error(): void
    {
        $post = Post::factory()->create();
        Like::factory()->for(User::factory())->for($post, 'likeable')->create();
        $this->actingAs(User::factory()->create());

        $response = $this->deleteJson('/api/v1/posts/' . $post->id . '/likes');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Like not found.']);
    }
}
