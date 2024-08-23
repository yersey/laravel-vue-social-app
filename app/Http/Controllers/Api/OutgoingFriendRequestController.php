<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\FriendRequest;
use App\Services\FriendService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Resources\FriendRequestResource;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\SelfFriendRequestException;
use App\Exceptions\FriendshipAlreadyExistsException;
use App\Exceptions\FriendRequestAlreadyExistsException;

class OutgoingFriendRequestController extends Controller
{
    function __construct(
        protected FriendService $service
    ) {}
    
    function store(User $user): JsonResponse
    {
        try {
            $friendRequest = $this->service->sendFriendRequest($user);
        } catch (SelfFriendRequestException $e) {
            return response()
                ->json(['message' => $e->getMessage()])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        } catch (FriendshipAlreadyExistsException $e) {
            return response()
                ->json(['message' => $e->getMessage()])
                ->setStatusCode(Response::HTTP_CONFLICT);
        } catch (FriendRequestAlreadyExistsException $e) {
            return response()
                ->json(['message' => $e->getMessage()])
                ->setStatusCode(Response::HTTP_CONFLICT);
        }

        return FriendRequestResource::make($friendRequest)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    function destroy(User $user, FriendRequest $friendRequest): JsonResponse
    {
        Gate::authorize('cancel-friend-request', $friendRequest);

        $friendRequest = $this->service->cancelFriendRequest($friendRequest);

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
