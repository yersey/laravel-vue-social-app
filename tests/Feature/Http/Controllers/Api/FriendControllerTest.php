<?php

namespace Tests\Feature\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FriendControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->hasRequestedFriendships(2)
            ->hasAcceptedFriendships(2)
            ->create();
    }

    public function test_friends_are_returned_correctly(): void
    {
        $response = $this->getJson('/api/v1/users/' . $this->user->id . '/friends');

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'surname',
                        'date_of_birth',
                        'created_at',
                        'avatar',
                    ]
                ]
            ]);
    }

    public function test_friend_is_deleted_successfully(): void
    {
        $this->actingAs($this->user);
        $friend = $this->user->friends->first();

        $response = $this->deleteJson('/api/v1/users/' . $this->user->id . '/friends/' . $friend->id);

        $response->assertStatus(204);
        $this->assertFalse($this->user->fresh()->friends->contains($friend));
    }
    
    public function test_deletion_of_friend_of_another_user_throws_error(): void
    {
        $this->actingAs(User::factory()->create());
        $friend = $this->user->friends->first();

        $response = $this->deleteJson('/api/v1/users/' . $this->user->id . '/friends/' . $friend->id);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Friend not found.']);
    }

    public function test_unauthenticated_friend_deletion_throws_error(): void
    {
        $friend = $this->user->friends->first();

        $response = $this->deleteJson('/api/v1/users/' . $this->user->id . '/friends/' . $friend->id);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
