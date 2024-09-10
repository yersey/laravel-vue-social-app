<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\DataTransferObjects\CommentDto;
use App\Http\Resources\V1\CommentResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCommentController extends Controller
{
    public function __construct(
        protected PostService $service
    ) {}

    public function index(Post $post): ResourceCollection 
    {
        $comments = $post->comments;

        return CommentResource::collection($comments);
    }

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
