<?php

namespace Tests\RankingsTests;

use App\Leaderboards\LeaderboardImpl;
use App\Leaderboards\LeaderboardItem;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_add_ranks_to_the_leaderboard_items()
    {
        $items = collect([]);

        $items->push(new LeaderboardItem((object) [
            'name' => 'Greg',
            'points' => 1,
            'user_id' => 1
        ]));

        $query = new LeaderboardImpl();
        $query->initialize($items, 1);
        $leaderboard = $query->build()->get();
        $this->assertEquals(1, $leaderboard->sections->first()->first()->rank);
    }
}
