<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use Uuids;

    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];
    public $incrementing = false;

    public function post()
    {
        return $this->hasMany(Post::class);
    }

}
