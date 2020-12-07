<?php

namespace Tests\RankingsTests;

use App\Leaderboards\LeaderboardImpl;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_add_ranks_to_the_leaderboard_items()
    {
        $items = collect([]);

        $items->push(createLeaderboardItemObj(1, 1, 1));

        $query = new LeaderboardImpl();
        $query->initialize($items, 1);
        $leaderboard = $query->build()->get();
        $this->assertEquals(1, $leaderboard->sections->first()->first()->rank);
    }
}
