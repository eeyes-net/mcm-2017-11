<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('index.post.index', [
            'posts' => Post::latest()->paginate(),
        ]);
    }

    public function show(Post $post)
    {
        return view('index.post.show', [
            'post' => $post,
        ]);
    }
}
