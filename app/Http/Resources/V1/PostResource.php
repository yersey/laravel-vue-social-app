<?php

namespace App\Http\Resources\V1;

use App\Hateoas\PostHateoas;
use Illuminate\Http\Request;
use App\Traits\HateoasResource;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    use HateoasResource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hateoas = new PostHateoas($this->resource, $request->user());

        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => UserResource::make($this->user),
            'created_at' => $this->created_at,
            'comments' => CommentResource::collection($this->comments),
            'likes_count' => $this->likes->count(),
            'is_liked' => $this->isLikedByUser($request->user()),
            '_links' => $this->links($hateoas)
        ];
    }
}
