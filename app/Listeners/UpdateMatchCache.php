<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;

class UpdateMatchCache
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        Cache::tags('matches')->flush();
    }
}
