<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\V1\CommentResource;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function __construct(
        protected CommentService $service
    ) {}
    
    public function show(Comment $comment): CommentResource
    {
        return CommentResource::make($comment);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        Gate::authorize('delete-comment', $comment);

        $this->service->delete($comment);
        
        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
