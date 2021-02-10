<?php

namespace Tests\RankingsTests;

use App\Leaderboards\CountryRanking;
use App\Leaderboards\CourseRankings;
use App\Leaderboards\LeaderboardBuilder;
use App\Leaderboards\LeaderBoardFactory;
use App\Leaderboards\WorldRanking;
use App\Models\Course;
use Illuminate\Contracts\Auth\Authenticatable;
use Tests\TestCase;

class LeaderboardFactoryTest extends TestCase
{

    /** @test */
    public function it_runs_initiate_build_transform_get()
    {

        $builder = $this->mock(LeaderboardBuilder::class, function ($mock) {
            $mock->shouldReceive('initialize')->once()->andReturnSelf();
            $mock->shouldReceive('build')->once()->andReturnSelf();
            $mock->shouldReceive('get')->once();
        });

        $user = $this->mock(Authenticatable::class, function ($mock) {
            $mock->shouldReceive('getAuthIdentifier');
        });

        $course = $this->mock(CountryRanking::class, function ($mock) {
            $mock->shouldReceive('get');
        });

        LeaderBoardFactory::getLeaderboard($builder, $user, $course);
    }
}
