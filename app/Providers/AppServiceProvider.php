<?php

namespace App\Providers;

use App\UserRankings\RankingsBuilderInterface;
use App\UserRankings\UserRankingsBuilder;
use App\UserRankings\CourseRankings;
use App\UserRankings\CourseRankingsQuery;
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
            CourseRankings::class,
            CourseRankingsQuery::class
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
