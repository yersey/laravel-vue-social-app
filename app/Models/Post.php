<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->comments->map->delete();
            //won't fire events
            $post->likes()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes() : MorphMany 
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedByLoggedInUser(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->likes->contains('user_id', $user->id);
    }
}
