<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use App\Traits\HateoasResource;
use App\Hateoas\FriendRequestHateoas;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendRequestResource extends JsonResource
{
    use HateoasResource;
    
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hateoas = new FriendRequestHateoas($this->resource, $request->user());

        return [
            'id' => $this->id,
            'sender' => UserResource::make($this->sender),
            'receiver' => UserResource::make($this->receiver),
            '_links' => $this->links($hateoas)
        ];
    }
}
