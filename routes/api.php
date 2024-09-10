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

//TODO move middlewares to controllers
Route::get('/ping', function (Request $request) {
    return 'pong';
});

Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::post('/images', [ImageController::class, 'store'])->name('images.store');

    Route::get('/user', [UserController::class, 'me'])->name('users.me');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::post('/users/{receiver}/friend-requests', [OutgoingFriendRequestController::class, 'store'])->name('outgoing-friend-requests.store');
    Route::delete('/users/{receiver}/friend-requests', [OutgoingFriendRequestController::class, 'destroy'])->name('outgoing-friend-requests.destroy');
    
    Route::get('/friend-requests', [IncomingFriendRequestController::class, 'index'])->name('friend-requests.index');
    Route::patch('/friend-requests/{friendRequest}', [IncomingFriendRequestController::class, 'update'])->name('friend-requests.update');
    Route::delete('/friend-requests/{friendRequest}', [IncomingFriendRequestController::class, 'destroy'])->name('friend-requests.destroy');

    Route::delete('/users/{user}/friends/{friend}', [FriendController::class, 'destroy'])->name('friends.destroy');
});

Route::middleware('set_user_if_authenticated')->group(function() {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/comments', [PostCommentController::class, 'index'])->name('post-comments.index');
    Route::get('/comments/{comment}', [CommentController::class, 'show'])->name('comments.show');
    Route::get('/comments/{comment}/comments', [CommentCommentController::class, 'index'])->name('comment-comments.index');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/friends', [FriendController::class, 'index'])->name('friends.index');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/comments', [PostCommentController::class, 'store'])->name('post-comments.store');
    Route::post('/posts/{post}/likes', [PostLikeController::class, 'store'])->name('post-likes.store');
    Route::delete('/posts/{post}/likes', [PostLikeController::class, 'destroy'])->name('post-likes.destroy');

    Route::post('/comments/{comment}/comments', [CommentCommentController::class, 'store'])->name('comment-comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/likes', [CommentLikeController::class, 'store'])->name('comment-likes.store');
    Route::delete('/comments/{comment}/likes', [CommentLikeController::class, 'destroy'])->name('comment-likes.destroy');
});
