<?php

namespace App\Hateoas;

use Illuminate\Support\Facades\Gate;

class FriendRequestHateoas extends Hateoas
{
    public function deleteLink(): ?array
    {
        if ($this->user && $this->resource->sender_id === $this->user->id) {
            return $this->link('delete', 'DELETE', route('outgoing-friend-requests.destroy', [
                'receiver' => $this->resource->receiver_id,
            ]));
        }
        
        return null;
    }

    public function acceptLink(): ?array
    {
        if ($this->user && Gate::forUser($this->user)->allows('respond-friend-request', $this->resource)) {
            return $this->link('accept', 'PATCH', route('friend-requests.update', ['friendRequest' => $this->resource->id]));
        }
        
        return null;
    }

    public function rejectLink(): ?array
    {
        if ($this->user && Gate::forUser($this->user)->allows('respond-friend-request', $this->resource)) {
            return $this->link('reject', 'DELETE', route('friend-requests.destroy', ['friendRequest' => $this->resource->id]));
        }
        
        return null;
    }

    public function senderLink(): array
    {
        return $this->link('sender', 'GET', route('users.show', ['user' => $this->resource->sender_id]));
    }

    public function receiverLink(): array
    {
        return $this->link('receiver', 'GET', route('users.show', ['user' => $this->resource->receiver_id]));
    }
}
