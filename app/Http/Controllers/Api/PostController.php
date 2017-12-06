<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        return Post::select(['id', 'title', 'created_at', 'updated_at'])->paginate();
    }

    public function show(Post $post)
    {
        return $post;
    }
}
