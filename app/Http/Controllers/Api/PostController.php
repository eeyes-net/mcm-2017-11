<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use App\Http\Resources\Post as PostResource;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index()
    {
        $posts = Cache::tags('posts')->remember('api_post_page_' . request('page'), 1440, function () {
            return PostResource::collection(Post::select(['id', 'title', 'created_at', 'updated_at'])->latest()->paginate());
        });
        return $posts;
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }
}
