<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserController extends Controller
{
    public function index(): ResourceCollection
    {
        $users = User::with([
            'requestedFriendships',
            'acceptedFriendships',
            'sentFriendRequests',
            'receivedFriendRequests'
        ])->get();

        return UserResource::collection($users);
    }

    public function me(Request $request): UserResource
    {
        $user = $request->user();

        return UserResource::make($user);
    }

    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        Gate::authorize('update-user', $user);

        //TODO service
        $user->name = $request->safe()->name;
        $user->surname = $request->safe()->surname;
        $user->date_of_birth = $request->safe()->date_of_birth;
        if ($request->safe()->remove_avatar) {
            $user->avatar_path = null;
        } else if ($request->safe()->avatar_path) {
            $user->avatar_path = $request->safe()->avatar_path;
        }
        $user->save();

        return UserResource::make($user);
    }
}
