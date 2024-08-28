<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function __construct(
        protected AuthManager $auth
    ) {}

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->safe()->only(['name', 'email', 'password']));

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    public function login(Request $request)
    {
        if (!$this->auth->attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'Bad credentials'], 401);
        }

        $request->user()->tokens()->delete();
        $token = $request->user()->createToken('token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
