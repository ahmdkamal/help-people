<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class DeletedNotification extends Model
{

    protected $table = 'deleted_notifications';

    protected $fillable = ['user_id', 'notification_id'];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id')->first();
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class , 'notification_id')->first();
    }
}
