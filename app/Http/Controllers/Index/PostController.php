<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index()
    {
        $posts = Cache::tags('posts')->remember('posts' . request('page'), 1440, function () {
            return Post::latest()->paginate();
        });
        return view('index.post.index', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post)
    {
        return view('index.post.show', [
            'post' => $post,
        ]);
    }
}
