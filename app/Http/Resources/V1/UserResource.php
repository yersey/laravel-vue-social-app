<?php

namespace App\Http\Resources\V1;

use App\Hateoas\UserHateoas;
use Illuminate\Http\Request;
use App\Traits\HateoasResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use HateoasResource;
    
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hateoas = new UserHateoas($this->resource, $request->user());

        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'date_of_birth' => $this->date_of_birth,
            'created_at' => $this->created_at,
            'avatar' => $this->avatar_path ? Storage::url($this->avatar_path) : null,
            'is_friend' => $this->when($request->user() && $this->friendsLoaded(), function () use ($request) {
                return $this->isFriendWith($request->user());
            }),
            '_links' => $this->links($hateoas)
        ];
    }
}
