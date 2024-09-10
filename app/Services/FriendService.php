<?php

namespace App\Services;

use App\Models\User;
use App\Models\FriendRequest;
use App\Exceptions\FriendNotFoundException;
use App\Exceptions\SelfFriendRequestException;
use App\Exceptions\FriendRequestNotFoundException;
use App\Exceptions\FriendshipAlreadyExistsException;
use App\Exceptions\FriendRequestAlreadyExistsException;

class FriendService
{
    public function unfriend(User $friend, User $user): void
    {
        if (!$friend->isFriendWith($user)) {
            throw new FriendNotFoundException();
        }

        $user->requestedFriendships()->detach($friend->id);
        $user->acceptedFriendships()->detach($friend->id);
    }

    function acceptFriendRequest(FriendRequest $friendRequest, User $user): void
    {
        $user->acceptedFriendships()->attach($friendRequest->sender_id);
        $friendRequest->delete();
    }

    function declineFriendRequest(FriendRequest $friendRequest): void
    {
        $friendRequest->delete();
    }

    public function sendFriendRequest(User $receiver, User $user): FriendRequest
    {
        if ($receiver->id === $user->id) {
            throw new SelfFriendRequestException();
        }

        if ($receiver->isFriendWith($user)) {
            throw new FriendshipAlreadyExistsException();
        }

        $sentFriendRequest = $user
            ->sentFriendRequests()
            ->where('receiver_id', $receiver->id)
            ->exists();
        if ($sentFriendRequest) {
            throw new FriendRequestAlreadyExistsException();
        }

        $receivedFriendRequest = $user
            ->receivedFriendRequests()
            ->where('sender_id', $receiver->id)
            ->exists();
        if ($receivedFriendRequest) {
            throw new FriendRequestAlreadyExistsException();
        }

        $friendRequest = FriendRequest::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
        ]);

        return $friendRequest;
    }

    public function cancelFriendRequest(User $receiver, User $user): void
    {
        $friendRequest = $user->sentFriendRequests()->where('receiver_id', $receiver->id)->first();

        if (!$friendRequest) {
            throw new FriendRequestNotFoundException();
        }

        $friendRequest->delete();
    }
}
