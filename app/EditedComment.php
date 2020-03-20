<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EditedComment extends Model
{
    use Uuids;
    protected $fillable = ['body', 'comment_id'];
    protected $table = 'edited_comments';
    public $incrementing = false;

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

}
