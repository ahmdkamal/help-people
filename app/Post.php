<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Uuids;

    protected $fillable = ['title', 'body', 'latitude', 'longitude', 'user_id', 'type_id', 'image', 'offer_help'];
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function unfollowers()
    {
        return $this->hasMany(Unfollower::class, 'post_id');
    }

}
