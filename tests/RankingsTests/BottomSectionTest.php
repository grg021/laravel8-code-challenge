<?php

namespace Tests\RankingsTests;

use App\Leaderboards\LeaderboardImpl;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BottomSectionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_bottom_three_from_list()
    {

        $items = collect([]);

        foreach (range(10, 1) as $n) {
            $items->push(createLeaderboardItemObj($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new LeaderboardImpl();
        $query->initialize($items, 1);
        $leaderboard = $query->build()->get();

        $this->assertCount(2, $leaderboard->getSections());
        $section = $leaderboard->getSections()->last();

        $this->assertEquals('3', $section[0]->userId);
        $this->assertEquals('2', $section[1]->userId);
        $this->assertEquals('1', $section[2]->userId);

    }

    /** @test */
    public function it_returns_bottom_four_from_list_if_user_is_3rd_to_last()
    {
        $items = collect([]);

        foreach (range(10, 1) as $n) {
            $items->push(createLeaderboardItemObj($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new LeaderboardImpl();
        $query->initialize($items, 3);
        $leaderboard = $query->build()->get();

        $this->assertCount(2, $leaderboard->getSections());
        $section = $leaderboard->getSections()->last();
        $this->assertCount(4, $section);


        $this->assertEquals('4', $section[0]->userId);
        $this->assertEquals('3', $section[1]->userId);
        $this->assertEquals('2', $section[2]->userId);
        $this->assertEquals('1', $section[3]->userId);
    }

    /** @test */
    public function it_returns_bottom_five_from_list_if_user_is_4th_to_last()
    {
        $items = collect([]);

        foreach (range(10, 1) as $n) {
            $items->push(createLeaderboardItemObj($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new LeaderboardImpl();
        $query->initialize($items, 4);
        $leaderboard = $query->build()->get();

        $this->assertCount(2, $leaderboard->getSections());
        $section = $leaderboard->getSections()->last();
        $this->assertCount(5, $section);


        $this->assertEquals('5', $section[0]->userId);
        $this->assertEquals('4', $section[1]->userId);
        $this->assertEquals('3', $section[2]->userId);
        $this->assertEquals('2', $section[3]->userId);
        $this->assertEquals('1', $section[4]->userId);
    }


}
