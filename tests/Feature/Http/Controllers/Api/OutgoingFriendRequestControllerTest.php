<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OutgoingFriendRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
    }

    public function test_friend_request_is_created_successfully(): void
    {
        $this->actingAs($this->user);
        $this->otherUser = User::factory()->create();

        $response = $this->postJson('/api/v1/users/' . $this->otherUser->id . '/friend-requests');

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'sender',
                    'receiver',
                ]
            ]);
        $this->assertNotEmpty($this->user->sentFriendRequests);
        $this->assertNotEmpty($this->otherUser->receivedFriendRequests);
    }

    public function test_duplicated_friend_request_creation_throws_error(): void
    {
        $this->actingAs($this->user);
        $this->otherUser = User::factory()->create();

        $response = $this->postJson('/api/v1/users/' . $this->otherUser->id . '/friend-requests');
        $response = $this->postJson('/api/v1/users/' . $this->otherUser->id . '/friend-requests');

        $response->assertStatus(409)
            ->assertJson(['message' => 'Friend request already exists.']);
        $this->assertCount(1, $this->user->sentFriendRequests);
    }

    public function test_self_friend_request_creation_throws_error(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/v1/users/' . $this->user->id . '/friend-requests');

        $response->assertStatus(400)
            ->assertJson(['message' => 'You cannot send a friend request to yourself.']);
    }

    public function test_unauthenticated_friend_request_creation_throws_error(): void
    {
        $this->otherUser = User::factory()->create();

        $response = $this->postJson('/api/v1/users/' . $this->otherUser->id . '/friend-requests');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_friend_request_is_deleted_successfully(): void
    {
        $this->actingAs($this->user);
        $this->otherUser = User::factory()->create();
        $friendRequest = $this->user->sentFriendRequests()->create([
            'receiver_id' => $this->otherUser->id
        ]);

        $response = $this->deleteJson('/api/v1/users/' . $this->otherUser->id . '/friend-requests/' . $friendRequest->id);

        $response->assertStatus(204);
        $this->assertNull($friendRequest->fresh());
    }

    public function test_unauthenticated_friend_request_deletion_throws_error(): void
    {
        $this->otherUser = User::factory()->create();
        $friendRequest = $this->user->sentFriendRequests()->create([
            'receiver_id' => $this->otherUser->id
        ]);

        $response = $this->deleteJson('/api/v1/users/' . $this->otherUser->id . '/friend-requests/' . $friendRequest->id);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_unauthorized_friend_request_deletion_throws_error(): void
    {
        $this->actingAs(User::factory()->create());
        $this->otherUser = User::factory()->create();
        $friendRequest = $this->user->sentFriendRequests()->create([
            'receiver_id' => $this->otherUser->id
        ]);

        $response = $this->deleteJson('/api/v1/users/' . $this->otherUser->id . '/friend-requests/' . $friendRequest->id);

        $response->assertStatus(403);
    }
}
