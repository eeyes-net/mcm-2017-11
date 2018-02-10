<?php

namespace App\Listeners;

use App\Events\PostsTableUpdated;
use Illuminate\Support\Facades\Cache;

class UpdatePostCache
{
    /**
     * Handle the event.
     *
     * @param  PostsTableUpdated  $event
     * @return void
     */
    public function handle(PostsTableUpdated $event)
    {
        Cache::tags('posts')->flush();
    }
}
