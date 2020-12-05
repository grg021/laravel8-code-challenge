<?php

namespace App\Providers;

use App\UserRankings\RankingsBuilderInterface;
use App\UserRankings\UserRankingsBuilder;
use App\UserRankings\RankingsQueryInterface;
use App\UserRankings\RankingsQuery;
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
            RankingsQueryInterface::class,
            RankingsQuery::class
        );
        $this->app->bind(
            RankingsBuilderInterface::class,
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
