<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Carbon::serializeUsing(function (\Carbon\Carbon $carbon) {
            $result = get_object_vars($carbon);
            $result['str'] = $carbon->toDateTimeString();
            $result['diff'] = $carbon->diffForHumans();
            return $result;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
