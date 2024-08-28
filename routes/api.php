<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ImageController;
use App\Http\Controllers\Api\V1\FriendController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\PostLikeController;
use App\Http\Controllers\Api\V1\CommentLikeController;
use App\Http\Controllers\Api\V1\PostCommentController;
use App\Http\Controllers\Api\V1\CommentCommentController;
use App\Http\Controllers\Api\V1\IncomingFriendRequestController;
use App\Http\Controllers\Api\V1\OutgoingFriendRequestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/*
  set_user_if_authenticated middleware assigns the authenticated user to the application context 
  if the user is logged in. 
  Allows access to user data even on routes without required authentication.
*/

Route::get('/ping', function (Request $request) {
    return 'pong';
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/images', [ImageController::class, 'store']);

    Route::get('/user', [UserController::class, 'me']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);

    Route::post('/users/{receiver}/friend-requests', [OutgoingFriendRequestController::class, 'store']);
    Route::delete('/users/{receiver}/friend-requests/{friendRequest}', [OutgoingFriendRequestController::class, 'destroy']);
    
    Route::get('/friend-requests', [IncomingFriendRequestController::class, 'index']);
    Route::patch('/friend-requests/{friendRequest}', [IncomingFriendRequestController::class, 'update']);
    Route::delete('/friend-requests/{friendRequest}', [IncomingFriendRequestController::class, 'destroy']);

    Route::get('/users/{user}/friends', [FriendController::class, 'index']);
    Route::delete('/users/{user}/friends/{friend}', [FriendController::class, 'destroy']);
});

Route::middleware('set_user_if_authenticated')->group(function() {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::post('/posts/{post}/comments', [PostCommentController::class, 'store']);
    Route::post('/posts/{post}/likes', [PostLikeController::class, 'store']);
    Route::delete('/posts/{post}/likes', [PostLikeController::class, 'destroy']);

    Route::post('/comments/{comment}/comments', [CommentCommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/comments/{comment}/likes', [CommentLikeController::class, 'store']);
    Route::delete('/comments/{comment}/likes', [CommentLikeController::class, 'destroy']);
});
