<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\DataTransferObjects\CommentDto;
use Symfony\Component\HttpFoundation\Response;

class PostCommentController extends Controller
{
    public function __construct(
        protected PostService $service
    ) {}

    public function store(CommentRequest $request, Post $post): JsonResponse
    {
        $comment = $this->service->comment(
            $post,
            CommentDto::fromRequest($request)
        );

        return CommentResource::make($comment)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
