<?php

namespace App\Jobs;

use App\Http\Controllers\NotificationsController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $notifiable_user, $post, $body, $title;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notifiable_user, $post, $body, $title)
    {
        $this->notifiable_user = $notifiable_user;
        $this->post = $post;
        $this->body = $body;
        $this->title = $title;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        NotificationsController::sendToUser($this->notifiable_user,
            [
                'title' => $this->title,
                "body" => "$this->body",
                "post_id" => $this->post->id
            ]);
    }
}
