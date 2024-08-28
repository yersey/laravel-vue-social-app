<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
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

    public function store(Request $request, Post $post): JsonResponse
    {
        try {
            $like = $this->service->like($post, $request->user());
        } catch (PostAlreadyLikedException $e) {
            return response()
                ->json(['message' => $e->getMessage()])
                ->setStatusCode(Response::HTTP_CONFLICT);
        }

        return LikeResource::make($like)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        try {
            $this->service->unlike($post, $request->user());
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
