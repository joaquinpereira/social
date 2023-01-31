<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'user_name' => $this->user->name,
            'ago' => $this->created_at->diffForHumans(),
            'user_avatar' => 'http://social/avatar.png',
            'is_liked' => $this->isLiked(),
            'likes_count' => $this->likesCount()
        ];
    }
}
