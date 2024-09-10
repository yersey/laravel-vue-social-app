<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use App\Hateoas\CommentHateoas;
use App\Traits\HateoasResource;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    use HateoasResource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hateoas = new CommentHateoas($this->resource, $request->user());

        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => UserResource::make($this->user),
            'created_at' => $this->created_at,
            'comments' => self::collection($this->comments),
            'likes_count' => $this->likes->count(),
            'is_liked' => $this->isLikedByUser($request->user()),
            '_links' => $this->links($hateoas)
        ];
    }
}
