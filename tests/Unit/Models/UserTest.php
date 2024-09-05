<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\FriendRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_friends_loaded_returns_true(): void
    {
        $this->user->load('requestedFriendships', 'acceptedFriendships');

        $result = $this->user->friendsLoaded();

        $this->assertTrue($result);
    }

    public function test_friends_loaded_when_partial_loaded_returns_false(): void
    {
        $this->user->load('acceptedFriendships');

        $result = $this->user->friendsLoaded();

        $this->assertFalse($result);
    }

    public function test_friend_requests_loaded_returns_true(): void
    {
        $this->user->load('receivedFriendRequests', 'sentFriendRequests');

        $result = $this->user->friendRequestRelationsLoaded();

        $this->assertTrue($result);
    }

    public function test_friend_requests_loaded_when_partial_loaded_returns_false(): void
    {
        $this->user->load('receivedFriendRequests');

        $result = $this->user->friendRequestRelationsLoaded();

        $this->assertFalse($result);
    }

    public function test_get_friends_attribute_returns_all_friend_users(): void
    {
        $this->user->requestedFriendships()->attach(User::factory()->create());
        $this->user->acceptedFriendships()->attach(User::factory()->create());

        $result = $this->user->friends;

        $this->assertCount(2, $result);
        $this->assertInstanceOf(User::class, $result->first());
    }

    public function test_get_friend_request_with_returns_sent_friend_request(): void
    {
        $otherUser = User::factory()->create();
        $this->user
            ->sentFriendRequests()
            ->save(FriendRequest::factory()->create(['receiver_id' => $otherUser->id]));

        $result = $this->user->getFriendRequestWith($otherUser);

        $this->assertInstanceOf(FriendRequest::class, $result);
    }

    public function test_get_friend_request_with_returns_received_friend_request(): void
    {
        $otherUser = User::factory()->create();
        $this->user
            ->receivedFriendRequests()
            ->save(FriendRequest::factory()->create(['sender_id' => $otherUser->id]));

        $result = $this->user->getFriendRequestWith($otherUser);

        $this->assertInstanceOf(FriendRequest::class, $result);
    }

    public function test_get_friend_request_with_returns_null(): void
    {
        $otherUser = User::factory()->create();

        $result = $this->user->getFriendRequestWith($otherUser);

        $this->assertNull($result);
    }

    public function test_is_friend_with_returns_true(): void
    {
        $otherUser = User::factory()->create();
        $this->user->requestedFriendships()->attach($otherUser);

        $result = $this->user->isFriendWith($otherUser);

        $this->assertTrue($result);
    }

    public function test_is_friend_with_returns_false(): void
    {
        $otherUser = User::factory()->create();

        $result = $this->user->isFriendWith($otherUser);

        $this->assertFalse($result);
    }
}
