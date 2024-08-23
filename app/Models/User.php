<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\FriendRequest;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',

    ];

    public function sentFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function receivedFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }

    public function requestedFriendships(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_1', 'user_2')->withPivot('created_at');
    }

    public function acceptedFriendships(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_2', 'user_1')->withPivot('created_at');
    }

    public function friendsLoaded(): bool
    {
        return $this->relationLoaded('requestedFriendships') && $this->relationLoaded('acceptedFriendships');
    }

    public function friendRequestRelationsLoaded(): bool
    {
        return $this->relationLoaded('sentFriendRequests') && $this->relationLoaded('receivedFriendRequests');
    }

    public function getFriendsAttribute(): Collection
    {
        return $this->requestedFriendships->merge($this->acceptedFriendships);
    }

    public function getFriendRequestWith(User $user): ?FriendRequest
    {
        return $this->sentFriendRequests->where('receiver_id', $user->id)->first()
            ?? $this->receivedFriendRequests->where('sender_id', $user->id)->first()
            ?? null;
    }

    public function isFriendWith(User $user): bool
    {
        return $this->friends->contains($user);
    }
}
