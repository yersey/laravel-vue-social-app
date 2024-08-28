<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\V1\CommentResource;
use App\DataTransferObjects\CommentDto;
use App\Exceptions\InvalidReplyDepthException;
use Symfony\Component\HttpFoundation\Response;

class CommentCommentController extends Controller
{
    public function __construct(
        protected CommentService $service
    ) {}

    public function store(CommentRequest $request, Comment $comment): JsonResponse
    {
        try {
            $reply = $this->service->store(
                $comment,
                CommentDto::fromRequest($request)
            );
        } catch (InvalidReplyDepthException $e) {
            return response()
                ->json(['message' => $e->getMessage()])
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return CommentResource::make($reply)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
