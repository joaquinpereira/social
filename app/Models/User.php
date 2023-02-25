<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
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
        'avatar'
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

    public function getAvatarAttribute()
    {
        return Storage::url($this->attributes['avatar']);
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

    public function friends()
    {
        $senderFriends = $this->belongsToMany(User::class, 'friendships', 'sender_id','recipient_id')
            ->wherePivot('status','accepted')->get();

        $recipientFriends = $this->belongsToMany(User::class, 'friendships', 'recipient_id', 'sender_id')
            ->wherePivot('status','accepted')->get();

        return $senderFriends->merge($recipientFriends);
    }

    public function savePicture($avatar)
    {
        $path = Storage::put('public/users', $avatar);
        Storage::delete($this->avatar);
        $this->avatar = $path;
        $this->save();
    }
}
