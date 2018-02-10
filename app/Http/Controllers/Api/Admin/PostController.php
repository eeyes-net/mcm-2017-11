<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\PostsTableUpdated;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::latest()->select(['id', 'title', 'created_at', 'updated_at'])->paginate();
    }

    public function store(Request $request)
    {
        $post = Post::create($request->validate([
            'title' => 'required',
            'content' => 'required',
        ]));
        event(new PostsTableUpdated());
        return $post;
    }

    public function show(Post $post)
    {
        return $post;
    }

    public function update(Request $request, Post $post)
    {
        $post->update($request->only([
            'title',
            'content',
            'create_at',
        ]));
        event(new PostsTableUpdated());
        return $post;
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return $post;
    }
}
