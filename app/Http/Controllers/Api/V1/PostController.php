<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PostRequest;
use App\DataTransferObjects\PostDto;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostController extends Controller
{
    public function __construct(
        protected PostService $service
    ) {}

    public function index(): ResourceCollection
    {
        $posts = Post::with([
            'user',
            'likes',
            'comments.user',
            'comments.likes',
            'comments.comments.likes',
            'comments.comments.user'
        ])->orderBy('id')->cursorPaginate(2);

        return PostResource::collection($posts);
    }

    public function show(Post $post): PostResource
    {
        return PostResource::make($post);
    }

    public function store(PostRequest $request): JsonResponse
    {
        $post = $this->service->store(
            PostDto::fromRequest($request)
        );

        return PostResource::make($post)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(PostRequest $request, Post $post): JsonResponse
    {
        Gate::authorize('update-post', $post);
        
        $post = $this->service->update(
            $post,
            PostDto::fromRequest($request)
        );

        return PostResource::make($post)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Post $post): JsonResponse
    {
        Gate::authorize('delete-post', $post);

        $this->service->delete($post);

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
