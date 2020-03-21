<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Uuids;
    protected $fillable = ['body', 'post_id', 'user_id', 'edited'];
    public $incrementing = false;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function edits()
    {
        return $this->hasMany(EditedComment::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class ,'parent_id');
    }

}
