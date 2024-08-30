<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\LikeResource;
use Symfony\Component\HttpFoundation\Response;

class PostLikeController extends Controller
{
    public function __construct(
        protected PostService $service
    ) {}

    public function store(Request $request, Post $post): JsonResponse
    {
        $like = $this->service->like($post, $request->user());

        return LikeResource::make($like)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        $this->service->unlike($post, $request->user());

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
