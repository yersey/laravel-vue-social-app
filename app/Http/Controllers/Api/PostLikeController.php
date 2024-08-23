<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Exceptions\LikeNotFoundException;
use App\Exceptions\PostAlreadyLikedException;
use Symfony\Component\HttpFoundation\Response;

class PostLikeController extends Controller
{
    public function __construct(
        protected PostService $service
    ) {}

    public function store(Post $post): JsonResponse
    {
        try {
            $like = $this->service->like($post);
        } catch (PostAlreadyLikedException $e) {
            return response()
                ->json(['message' => $e->getMessage()])
                ->setStatusCode(Response::HTTP_CONFLICT);
        }

        return LikeResource::make($like)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(Post $post): JsonResponse
    {
        try {
            $this->service->unlike($post);
        } catch (LikeNotFoundException $e) {
            return response()
                ->json(['message' => $e->getMessage()])
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
