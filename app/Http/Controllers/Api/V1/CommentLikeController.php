<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\LikeResource;
use Symfony\Component\HttpFoundation\Response;

class CommentLikeController extends Controller
{
    public function __construct(
        protected CommentService $service
    ) {}
    
    public function store(Request $request, Comment $comment): JsonResponse
    {
        $like = $this->service->like($comment, $request->user());

        return LikeResource::make($like)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        $this->service->unlike($comment, $request->user());

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
