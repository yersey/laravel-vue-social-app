<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FriendRequest;
use App\Services\FriendService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\V1\FriendRequestResource;

class OutgoingFriendRequestController extends Controller
{
    function __construct(
        protected FriendService $service
    ) {}
    
    function store(Request $request, User $receiver): JsonResponse
    {
        $friendRequest = $this->service->sendFriendRequest($receiver, $request->user());

        return FriendRequestResource::make($friendRequest)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    function destroy(User $receiver, FriendRequest $friendRequest): JsonResponse
    {
        Gate::authorize('cancel-friend-request', $friendRequest);

        $friendRequest = $this->service->cancelFriendRequest($friendRequest);

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
