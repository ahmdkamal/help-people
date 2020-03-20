<?php

namespace App\Http\Controllers;

use App\Http\Requests\AllPostsRequest;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\PostResource;
use App\Post;
use \Auth;
use \DB;

class PostsController extends Controller
{

    public function index(AllPostsRequest $allPostsRequest)
    {
        $latitude = request()->latitude;
        $longitude = request()->longitude;
        $type_id = request()->type_id;
        $own = request()->own ? (boolean)request()->own : null;
        $offer_help = request()->offer_help ? (boolean)request()->offer_help : null;
        $per_page = request()->per_page && is_int(request()->per_page) ? request()->per_page : 10;

        $posts = Post::query();
        $posts = $offer_help ? $offer_help->where('offer_help', $offer_help) : $posts;
        $posts = $own == true
            ? $posts->where('user_id', Auth::id())
            : $posts->where('user_id', '!=', Auth::id());
        $posts = $type_id ? $posts->where('type_id', $type_id) : $posts;

        $posts = !$own ? $posts
            ->select(DB::raw('*, ( 6367 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) )
                      * cos( radians( longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') )
                      * sin( radians( latitude ) ) ) ) AS distance'))->orderBy('distance', 'asc')
            : $posts;

        $posts = $posts->orderBy('created_at', 'desc')->paginate($per_page);

        return response()->json([
            'data' => PostResource::collection($posts),
            'message' => 'Successfully',
            'pagination' => PaginationResource::make($posts->toArray())
        ], 200);
    }

    public function store(PostRequest $postRequest)
    {
        request()->merge(['user_id' => Auth::id()]);


        $post = Post::create(request()->all());

        if (request()->has('image')) {
            $image = $this->uploadImage(request('image'));
            $post->update(['image' => $image]);
        }

        $post = PostResource::make($post);

        return response()->json([
            'data' => $post,
            'message' => 'Successfully',
            'pagination' => null
        ], 201);
    }

    public function show()
    {
        $post = Post::where('id', request('post'))->firstOrFail();
        $post = PostResource::make($post);

        return response()->json([
            'data' => $post,
            'message' => 'Successfully',
            'pagination' => null
        ], 200);
    }

    public function update(PostRequest $postRequest)
    {
        $post = Post::where('user_id', Auth::id())->where('id', request('post'))->firstOrFail();

        if (request()->has('image')) {
            $image = $this->uploadImage(request('image'));
            request()->merge(['image' => $image]);
        }

        $post->update(request()->all());

        $post = PostResource::make($post);

        return response()->json([
            'data' => $post,
            'message' => 'Successfully',
            'pagination' => null
        ], 200);
    }

    public function destroy()
    {
        $post = Post::where('id', request('post'))->firstOrFail();
        $post->delete();
        return response()->json([], 204);
    }

    function uploadImage($image)
    {
        $photoName = time(). '.' . $image->getClientOriginalExtension();
        $image->move(public_path('/posts/'.auth()->id()), $photoName);
        return auth()->id()."/".$photoName;
    }

}
