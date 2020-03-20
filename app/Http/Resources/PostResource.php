<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'distance' => isset($this->distance) ? $this->distance : 0,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'user' => $this->user,
            'type' => $this->type,
            'image' => $this->image ? asset('/posts/'.$this->image): '',
            'comments' => CommentResource::collection($this->comments),
            'created_at' => strtotime($this->created_at)
        ];
    }
}
