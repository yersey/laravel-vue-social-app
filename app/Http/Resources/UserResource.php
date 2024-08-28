<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'date_of_birth' => $this->date_of_birth,
            'created_at' => $this->created_at,
            'avatar' => $this->avatar_path ? Storage::url($this->avatar_path) : null,
            'is_friend' => $this->when($request->user() && $this->friendsLoaded(), function () use ($request) {
                return $this->isFriendWith($request->user());
            }),
            'friend_request' => $this->when($request->user() && $this->friendRequestRelationsLoaded(), function () use ($request) {
                if ($friendRequest = $this->getFriendRequestWith($request->user())) {
                    return [
                        'id' => $friendRequest->id,
                        'sender_id' => $friendRequest->sender_id,
                        'receiver_id' => $friendRequest->receiver_id
                    ];
                }
            })
        ];
    }
}
