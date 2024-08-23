<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Exceptions\LikeNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\CommentAlreadyLikedException;

class CommentLikeController extends Controller
{
    public function __construct(
        protected CommentService $service
    ) {}
    
    public function store(Comment $comment): JsonResponse
    {
        try {
            $like = $this->service->like($comment);
        } catch (CommentAlreadyLikedException $e) {
            return response()
                ->json(['message' => $e->getMessage()])
                ->setStatusCode(Response::HTTP_CONFLICT);
        }

        return LikeResource::make($like)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        try {
            $this->service->unlike($comment);
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
