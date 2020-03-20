<?php

namespace App\Http\Controllers;

use App\DeletedNotification;
use App\Device;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\PaginationResource;
use App\Notification;
use App\Post;
use Carbon\Carbon;

class ApiMobNotifications extends Controller
{

    public function updateDevice()
    {
        Device::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'device_id' => \request()->device_id
            ], [
                'device_token' => \request()->device_token,
                'device_type' => \request()->device_type,
            ]
        );

        return response()->json([
            'data' => [],
            'message' => 'Device Added',
            'pagination' => null
        ], 200);
    }

    public function notifications()
    {
        $per_page = request()->has('per_page') ? request()->get('per_page') : config('per_page');

        $date = new Carbon();
        $date->subWeek();

        $notifications = Notification::where(function ($q) {
            $q->where(function ($q) {
                $q->where('user_id', null)->orWhere('user_id', auth()->id());
            });
        })->where(function ($q) use ($date) {
            $q->whereDate('created_at', '>=', $date)
                ->whereDate('created_at', '>=', auth()->user()->created_at);
        })->whereNotIn('id',
            DeletedNotification::where('user_id', auth()->id())->get()->pluck('notification_id')->toArray()
        )
            ->orderBy('id', 'desc')->paginate($per_page);

        return response()->json([
            'data' => NotificationResource::collection($notifications),
            'message' => 'Successfully',
            'pagination' => PaginationResource::make($notifications->toArray())
        ], 200);
    }

    public function delete()
    {
        $notification = Notification::where('id', \request()->notification)
            ->where('user_id', null)->orWhere('user_id', auth()->id())->firstOrFail();
        DeletedNotification::firstOrCreate(['notification_id' => $notification->id, 'user_id' => auth()->id()]);
        return response()->json([], 204);
    }

    public function unfollow()
    {
        $post = Post::where('id', request('post'))->firstOrFail();
        $post->unfollowers(['user_id' => auth()->id() ]);

        return response()->json([], 204);
    }
}
