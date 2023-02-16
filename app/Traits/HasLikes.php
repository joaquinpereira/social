<?php

namespace App\Traits;

use App\Models\Like;
use App\Events\ModelLiked;
use App\Events\ModelUnliked;
use Illuminate\Support\Str;

trait HasLikes
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like()
    {
        $this->likes()->firstOrCreate([
            'user_id' => auth()->id()
        ]);

        ModelLiked::dispatch($this);
    }

    public function unlike()
    {
        $this->likes()->where([
            'user_id' => auth()->id()
        ])->delete();
info('pasa por aqui');
        ModelUnliked::dispatch($this);
    }

    public function isLiked()
    {
        return $this->likes()->where('user_id',auth()->id())->exists();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function eventChannelName()
    {
        return strtolower(Str::plural(class_basename($this))) . '.' . $this->getKey() . '.likes';
    }
}
