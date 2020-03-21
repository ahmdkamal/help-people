<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Jobs\SendNotificationJob;
use App\Notification;
use App\Post;
use App\Unfollower;

class CommentsController extends Controller
{

    public function store(CommentRequest $commentRequest)
    {
        $post = Post::where('id', request('post'))->firstOrFail();
        request()->merge(['user_id' => auth()->id()]);
        $comment = $post->comments()->where('parent_id', null)->create(request()->all());

        $unfollowers = Unfollower::where('post_id', $post->id)->distinct()->pluck('user_id')->toArray();
        $unfollowers[] = auth()->user()->id;


        $users = Comment::where('post_id', $post->id)
            ->where('parent_id', request()->parent_id)
            ->whereNotIn('user_id', $unfollowers)->distinct()
            ->pluck('user_id')->toArray();

        $users[] = $post->user_id;

        $body = request('body');
        if( strlen( $body ) > 50 ) {
            $body = substr( $body, 0, 50 ) . '...';
        }

        $commented_user = auth()->user()->name;
        $title = request()->parent_id
            ? "$commented_user commented to a post you are following"
            : "$commented_user replied to a post you are following";

        foreach ($users as $user) {

            Notification::create([
                'user_id' => $user,
                'title' => $title,
                'body' => $body,
                "post_id" => $post->id,
            ]);
            SendNotificationJob::dispatch($user, $commented_user, $post->id, $body, $title);
        }

        return response()->json([
            'data' => CommentResource::make($comment),
            'message' => 'Successfully',
            'pagination' => null
        ], 201);
    }

    public function edit(CommentRequest $commentRequest)
    {
        $post = Post::where('id', request('post_id'))->firstOrFail();
        $comment = $post->comments()->where('user_id', auth()->id())
            ->where('id', request('comment'))->firstOrFail();
        $comment->edits(['added_at' => $comment->updated_at]);

        request()->merge(['edited' => true]);
        $comment->update(request()->all());

        return response()->json([
            'data' => CommentResource::make($comment),
            'message' => 'Successfully',
            'pagination' => null
        ], 200);
    }
}
