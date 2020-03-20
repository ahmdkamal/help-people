<?php
namespace App;

use Ramsey\Uuid\Uuid;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $generateId = Uuid::uuid1();
            $generateId = $generateId->toString();

            $model->id = $generateId;
        });
    }

}
