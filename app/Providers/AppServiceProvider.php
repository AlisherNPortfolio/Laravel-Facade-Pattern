<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DG\Twitter\Twitter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('twitter-poster', function () {
            $config = config('services.twitter');
            return new Twitter($config['api_key'], $config['api_secret'], $config['api_token'], $config['api_token_secret']);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
