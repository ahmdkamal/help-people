<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id' => $this->id ,
            'body' => $this->body,
            'title' => $this->title,
            'post' => PostResource::make($this->post),
            'notification_date' => strtotime($this->created_at),
        ];
    }
}
