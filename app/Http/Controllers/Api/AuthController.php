<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->safe()->only(['name', 'email', 'password']));

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    public function login(Request $request)
    {
        if (!auth()->attempt($request->only(['email', 'password']))) {
            return response()->json(['error' => 'Bad credentials'], 401);
        }

        auth()->user()->tokens()->delete();
        $token = auth()->user()->createToken('token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
