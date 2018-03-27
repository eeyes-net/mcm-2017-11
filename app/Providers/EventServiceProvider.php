<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\PostsTableUpdated::class => [
            \App\Listeners\UpdatePostCache::class,
        ],
        \App\Events\MatchesTableUpdated::class => [
            \App\Listeners\UpdateMatchCache::class,
        ],
        \App\Events\MatchTeamCountUpdated::class => [
            \App\Listeners\UpdateMatchCache::class,
        ],
        \App\Events\EvilUserInput::class => [
            \App\Listeners\BanUser::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
