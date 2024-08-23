<?php

namespace App\Services;

use App\Models\User;
use App\Models\FriendRequest;
use App\Exceptions\FriendNotFoundException;
use App\Exceptions\SelfFriendRequestException;
use App\Exceptions\FriendshipAlreadyExistsException;
use App\Exceptions\FriendRequestAlreadyExistsException;

class FriendService
{
    public function unfriend(User $friend): void
    {
        if (!$friend->isFriendWith(auth()->user())) {
            throw new FriendNotFoundException();
        }

        auth()->user()->requestedFriendships()->detach($friend->id);
        auth()->user()->acceptedFriendships()->detach($friend->id);
    }

    function acceptFriendRequest(FriendRequest $friendRequest): void
    {
        auth()->user()->acceptedFriendships()->attach($friendRequest->sender_id);
        $friendRequest->delete();
    }

    function declineFriendRequest(FriendRequest $friendRequest): void
    {
        $friendRequest->delete();
    }

    public function sendFriendRequest($user): FriendRequest
    {
        if ($user->id === auth()->id()) {
            throw new SelfFriendRequestException();
        }

        if ($user->isFriendWith(auth()->user())) {
            throw new FriendshipAlreadyExistsException();
        }

        $sentFriendRequest = auth()->user()
            ->sentFriendRequests()
            ->where('receiver_id', $user->id)
            ->exists();
        if ($sentFriendRequest) {
            throw new FriendRequestAlreadyExistsException();
        }

        $receivedFriendRequest = auth()->user()
            ->receivedFriendRequests()
            ->where('sender_id', $user->id)
            ->exists();
        if ($receivedFriendRequest) {
            throw new FriendRequestAlreadyExistsException();
        }

        $friendRequest = FriendRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
        ]);

        return $friendRequest;
    }

    public function cancelFriendRequest(FriendRequest $friendRequest): void
    {
        $friendRequest->delete();
    }
}
