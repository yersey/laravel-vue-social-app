<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncomingFriendRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->hasReceivedFriendRequests(2)
            ->create();
    }

    public function test_incoming_friend_requests_are_returned_correctly(): void
    {
        $this->actingAs($this->user);

        $response = $this->getJson('/api/v1/friend-requests');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'sender',
                        'receiver',
                    ]
                ]
            ]);
    }

    public function test_friend_request_is_accepted_successfully(): void
    {
        $this->actingAs($this->user);
        $receivedFriendRequest = $this->user->receivedFriendRequests()->first();

        $response = $this->patchJson('/api/v1/friend-requests/' . $receivedFriendRequest->id);

        $response->assertStatus(200);
        $this->assertNull($receivedFriendRequest->fresh());
        $this->assertCount(1, $this->user->friends);
    }

    public function test_unauthenticated_friend_request_acceptance_throws_error(): void
    {
        $receivedFriendRequest = $this->user->receivedFriendRequests()->first();

        $response = $this->patchJson('/api/v1/friend-requests/' . $receivedFriendRequest->id);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_unauthorized_friend_request_acceptance_throws_error(): void
    {
        $this->actingAs(User::factory()->create());
        $receivedFriendRequest = $this->user->receivedFriendRequests()->first();

        $response = $this->patchJson('/api/v1/friend-requests/' . $receivedFriendRequest->id);

        $response->assertStatus(403);
    }

    public function test_friend_request_is_declined_successfully(): void
    {
        $this->actingAs($this->user);
        $receivedFriendRequest = $this->user->receivedFriendRequests()->first();

        $response = $this->deleteJson('/api/v1/friend-requests/' . $receivedFriendRequest->id);

        $response->assertStatus(204);
        $this->assertNull($receivedFriendRequest->fresh());
    }

    public function test_unauthenticated_friend_request_decline_throws_error(): void
    {
        $receivedFriendRequest = $this->user->receivedFriendRequests()->first();

        $response = $this->deleteJson('/api/v1/friend-requests/' . $receivedFriendRequest->id);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_unauthorized_friend_request_decline_throws_error(): void
    {
        $this->actingAs(User::factory()->create());
        $receivedFriendRequest = $this->user->receivedFriendRequests()->first();

        $response = $this->deleteJson('/api/v1/friend-requests/' . $receivedFriendRequest->id);

        $response->assertStatus(403);
    }
}
