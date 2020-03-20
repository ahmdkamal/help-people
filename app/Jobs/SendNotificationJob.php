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
    private $notifiable_user, $commented_user, $post_id, $body;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notifiable_user, $commented_user, $post_id, $body)
    {
        $this->notifiable_user = $notifiable_user;
        $this->commented_user = $commented_user;
        $this->post_id = $post_id;
        $this->body = $body;
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
                'title' => "$this->commented_user commented to a post you are following",
                "body" => "$this->body",
                "post_id" => $this->post_id
            ]);
    }
}
