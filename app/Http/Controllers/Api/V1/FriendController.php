<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FriendService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FriendController extends Controller
{
    function __construct(
        protected FriendService $service
    ) {}

    public function index(User $user): ResourceCollection
    {
        return UserResource::collection($user->friends);
    }

    function destroy(Request $request, User $user, User $friend): JsonResponse
    {
        $this->service->unfriend($friend, $request->user());

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
