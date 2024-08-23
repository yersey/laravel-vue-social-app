<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\FriendService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Exceptions\FriendNotFoundException;
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

    function destroy(User $user, User $friend): JsonResponse
    {
        try {
            $this->service->unfriend($friend);
        } catch (FriendNotFoundException $e) {
            return response()
                ->json(['message' => $e->getMessage()])
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
