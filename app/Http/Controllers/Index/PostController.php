<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index()
    {
        $posts = Cache::tags('posts')->remember('post_page_' . Paginator::resolveCurrentPage(), 1440, function () {
            return Post::select(['id', 'title', 'created_at', 'updated_at'])->latest()->paginate();
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
