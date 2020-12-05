<?php

namespace App\Providers;

use App\Api\SectionsBuilder;
use App\Api\UserRankingsBuilder;
use App\Api\UserRankingsInterface;
use App\Api\UserRankingsQuery;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserRankingsInterface::class,
            UserRankingsQuery::class
        );
        $this->app->bind(
            SectionsBuilder::class,
            UserRankingsBuilder::class
        );
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
