<?php

namespace Tests\RankingsTests;

use App\Leaderboards\LeaderboardImpl;
use App\Leaderboards\LeaderboardItem;
use App\Models\RankItem;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_transform_leaderboard_items_into_resource()
    {
        $items = collect([]);

        $items->push(new LeaderboardItem((object) [
            'points' => 1,
            'user_id' => 1
        ]));

        $query = new LeaderboardImpl();
        $query->initialize($items, 1);
        $sections = $query->build()->transform(RankItem::class)->get();
        $this->assertInstanceOf(RankItem::class, $sections->first()->first());
    }

    /** @test */
    public function it_add_ranks_to_the_leaderboard_items()
    {
        $items = collect([]);

        $items->push(new LeaderboardItem((object) [
            'points' => 1,
            'user_id' => 1
        ]));

        $query = new LeaderboardImpl();
        $query->initialize($items, 1);
        $sections = $query->build()->transform(RankItem::class)->get();
        $this->assertEquals(1, $sections->first()->first()->rank);
    }
}
