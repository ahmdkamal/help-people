<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unfollower extends Model
{
    use Uuids;
    protected $fillable = ['user_id', 'post_id'];
}
