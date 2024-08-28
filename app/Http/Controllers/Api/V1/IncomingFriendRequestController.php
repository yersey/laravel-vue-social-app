<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\FriendRequest;
use App\Services\FriendService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\FriendRequestResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IncomingFriendRequestController extends Controller
{
    function __construct(
        protected FriendService $service
    ) {}

    public function index(Request $request): ResourceCollection
    {
        $friendRequests = $request->user()->receivedFriendRequests;

        return FriendRequestResource::collection($friendRequests);
    }

    public function update(Request $request, FriendRequest $friendRequest): JsonResponse
    {
        Gate::authorize('respond-friend-request', $friendRequest);

        $this->service->acceptFriendRequest($friendRequest, $request->user());

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(FriendRequest $friendRequest): JsonResponse
    {
        Gate::authorize('respond-friend-request', $friendRequest);

        $this->service->declineFriendRequest($friendRequest);

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
