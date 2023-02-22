<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'first_name',
        'last_name',
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

    protected $appends = ['avatar'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function friendshipRequestsReceived()
    {
        return $this->hasMany(FriendShip::class, 'recipient_id');
    }

    public function friendshipRequestsSent()
    {
        return $this->hasMany(FriendShip::class, 'sender_id');
    }

    public function link()
    {
        return route('users.show', $this);
    }

    public function avatar()
    {
        return 'http://social/avatar.png';
    }

    public function getAvatarAttribute()
    {
        return $this->avatar();
    }

    public function sendFriendRequestTo($recipient)
    {
        return $this->friendshipRequestsSent()->firstOrCreate([
            'recipient_id' => $recipient->id
        ]);
    }

    public function acceptFriendRequestFrom($sender)
    {
        $friendship = $this->friendshipRequestsReceived()->where([
            'sender_id' => $sender->id,
        ])->first();

        $friendship->update(['status' => 'accepted']);

        return $friendship;
    }

    public function denyFriendRequestFrom($sender)
    {
        $friendship = $this->friendshipRequestsReceived()->where([
            'sender_id' => $sender->id,
        ])->first();

        $friendship->update(['status' => 'denied']);

        return $friendship;
    }
}
