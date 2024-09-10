<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FriendService;
use Illuminate\Http\JsonResponse;
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

    function destroy(Request $request, User $receiver): JsonResponse
    {
        $this->service->cancelFriendRequest($receiver, $request->user());

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
