<?php

namespace Tests\RankingsTests;

use App\Leaderboards\CourseRankings;
use App\Leaderboards\LeaderboardBuilder;
use App\Leaderboards\LeaderBoardFactory;
use Tests\TestCase;

class LeaderboardFactoryTest extends TestCase
{

    /** @test */
    public function it_runs_initiate_build_transform_get()
    {

        $bulder = $this->mock(LeaderboardBuilder::class, function ($mock) {
            $mock->shouldReceive('initialize')->once()->andReturnSelf();
            $mock->shouldReceive('build')->once()->andReturnSelf();
            $mock->shouldReceive('get')->once();
        });

        $rankings = $this->mock(CourseRankings::class, function ($mock) {
            $mock->shouldReceive('get');
        });

        (new LeaderBoardFactory($bulder))->getLeaderboard($rankings, 1);
    }
}
