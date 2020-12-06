<?php

namespace Tests\RankingsTests;

use App\Leaderboards\LeaderboardImpl;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MiddeSectionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_three_items_with_the_logged_in_user_in_the_middle()
    {
        $items = collect([]);

        foreach (range(10, 1) as $n) {
            $items->push(createLeaderboardItem($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new LeaderboardImpl();
        $query->initialize($items, 5);
        $sections = $query->build()->get()->getSections();


        $this->assertCount(3, $sections);
        $this->assertCount(3, $sections[0]);
        $this->assertCount(3, $sections[1]);
        $this->assertCount(3, $sections[2]);
    }

    /** @test */
    public function it_returns_middle_section_with_user_padding()
    {
        $items = collect([]);

        foreach (range(10, 1) as $n) {
            $items->push(createLeaderboardItem($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new LeaderboardImpl();
        $query->initialize($items, 5);
        $sections = $query->build()->get()->getSections();
        $this->assertCount(3, $sections[1]);
        $this->assertEquals(5, $sections[1][1]->userId);
    }

}
