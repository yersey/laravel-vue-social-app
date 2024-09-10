<?php

namespace App\Hateoas;

use Illuminate\Support\Facades\Gate;

class UserHateoas extends Hateoas
{
    public function selfLink(): array
    {
        return $this->link('self', 'GET', route('users.show', ['user' => $this->resource]));
    }

    public function updateLink(): ?array
    {
        if ($this->user && Gate::forUser($this->user)->allows('update-user', $this->resource)) {
            return $this->link('update', 'PUT', route('users.update', ['user' => $this->resource->id]));
        }
        
        return null;
    }

    public function sendFriendRequestLink(): ?array
    {
        if (
            $this->user
            && $this->resource->id !== $this->user->id
            && !$this->resource->isFriendWith($this->user)
            && !$this->resource->getFriendRequestWith($this->user)
        ) {
            return $this->link('send-friend-request', 'POST', route(
                'outgoing-friend-requests.store',
                ['receiver' => $this->resource->id]
            ));
        }
        
        return null;
    }

    public function deleteFriendRequestLink(): ?array
    {
        if ($this->user && $this->user->sentFriendRequests->contains('receiver_id', $this->resource->id)) {
            return $this->link('delete-friend-request', 'DELETE', route(
                'outgoing-friend-requests.destroy',
                ['receiver' => $this->resource->id]
            ));
        }

        return null;
    }

    public function acceptFriendRequestLink(): ?array
    {
        if (
            $this->user
            && $friendRequest = $this->user->receivedFriendRequests->where('sender_id', $this->resource->id)->first()
        ) {
            return $this->link('accept-friend-request', 'PATCH', route('friend-requests.update', ['friendRequest' => $friendRequest->id]));
        }
        
        return null;
    }

    public function rejectFriendRequestLink(): ?array
    {
        if (
            $this->user
            && $friendRequest = $this->user->receivedFriendRequests->where('sender_id', $this->resource->id)->first()
        ) {
            return $this->link('reject-friend-request', 'DELETE', route('friend-requests.destroy', ['friendRequest' => $friendRequest->id]));
        }
        
        return null;
    }

    public function removeFriendLink(): ?array
    {
        if (
            $this->user
            && $this->resource->isFriendWith($this->user) 
        ) {
            return $this->link('remove-friend', 'DELETE', route(
                'friends.destroy',
                [
                    'user' => $this->user->id,
                    'friend' => $this->resource->id,
                ]
            ));
        }
        
        return null;
    }

    public function friendsLink(): array
    {
        return $this->link('friends', 'GET', route('friends.index', ['user' => $this->resource->id]));
    }
}
