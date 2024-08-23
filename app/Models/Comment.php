<?php

namespace App\Models;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot() {
        parent::boot();

        static::deleting(function ($comment) {
            if (!$comment->isCommentReply()) {
                $comment->comments->map->delete();
            }
            //won't fire events
            $comment->likes()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(self::class, 'commentable');
    }

    public function likes() : MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedByLoggedInUser(): bool
    {
        return $this->likes->contains('user_id', auth()->id() ?? 0);
    }
    
    public function isCommentReply(): bool
    {
        return $this->commentable_type === self::class;
    }
}
