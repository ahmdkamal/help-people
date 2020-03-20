<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use Uuids;

    protected $fillable = ['title', 'body', 'latitude', 'devices', 'user_id', 'post_id'];
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
