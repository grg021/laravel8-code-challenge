<?php

namespace Tests\RankingsTests;

use App\Leaderboards\LeaderboardImpl;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LeaderboardImplTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_always_displays_top_3_bottom_3()
    {
        $items = collect([]);

        foreach (range(10, 1) as $key => $item) {
            $items->push((object) [
                'points' => '1',
                'userId' => $item,
                'rank' => 1,
            ]);
        }

        $query = new LeaderboardImpl();
        $query->initialize($items, 1);
        $leaderboard = $query->build()->get();
        $sections = $leaderboard->sections;
        $this->assertCount(2, $sections);
        $this->assertCount(3, $sections->first());
        $this->assertCount(3, $sections->last());
    }

    /**
     * @test
     * @dataProvider itemsAndUserId
     * @param $items
     * @param $userId
     */
    public function it_returns_2_sections_if_user_is_in_top_or_bottom_4($items, $userId)
    {
        $query = new LeaderboardImpl();
        $query->initialize($items, $userId);
        $this->assertCount(2, $query->build()->get()->sections);
    }

    public function itemsAndUserId()
    {
        $items = collect([]);

        foreach (range(21, 1) as $key => $item) {
            $items->push((object) [
                'points' => $item,
                'userId' => $item,
                'rank' => $key + 1,
            ]);
        }
        return [
            [clone $items, 1],
            [clone $items, 2],
            [clone $items, 3],
            [clone $items, 4],
            [clone $items, 21],
            [clone $items, 20],
            [clone $items, 19],
            [clone $items, 18],
        ];
    }


    /** @test */
    public function it_returns_3_sections_if_user_is_in_middle()
    {

        $items = collect([]);

        foreach (range(21, 1) as $key => $item) {
            $items->push((object) [
                'points' => $item,
                'userId' => $item,
                'rank' => $key + 1,
            ]);
        }

        $query = new LeaderboardImpl();
        $query->initialize($items, 10);

        $sections = $query->build()->get()->sections;

        $this->assertEquals(3, $sections->count());
    }


    /** @test */
    public function it_puts_higher_priority_for_logged_in_user_when_score_is_the_same()
    {
        $items = collect([]);

        foreach (range(3, 1) as $key => $item) {
            $items->push((object) [
                'points' => '1',
                'userId' => $item,
                'rank' => 1,
            ]);
        }

        $query = new LeaderboardImpl();
        $query->initialize($items, 1);
        $leaderboard = $query->build()->get();

        $this->assertEquals(1, $leaderboard->sections->first()->first()->userId);
    }

    /** @test */
    public function it_highlights_logged_in_user_only()
    {
        $items = collect([]);

        foreach (range(2, 1) as $key => $item) {
            $items->push((object) [
                'points' => '1',
                'userId' => $item,
                'rank' => 1,
                'highlight' => 0,
                'points_diff' => '0'
            ]);
        }

        $query = new LeaderboardImpl();
        $query->initialize($items, 1);
        $leaderboard = $query->build()->get();
        $section = $leaderboard->sections;

        $this->assertSameSize($items, $section->first());
        $this->assertEquals(1, $section->first()[0]->highlight);
        $this->assertEquals(0, $section->first()[1]->highlight);
    }

    /** @test */
    public function it_only_adds_points_diff_on_users_ranked_higher()
    {
        $items = collect([]);

        foreach (range(10, 1) as $key => $item) {
            $items->push(createLeaderboardItem($item, $item, $key + 1));
        }

        $query = new LeaderboardImpl();
        $query->initialize($items, 5);
        $leaderboard = $query->build()->get();
        $sections = $leaderboard->sections;

        $first = $sections->first();
        $mid = $sections[1];
        $last = $sections->last();

        $last->each(function ($item) {
            $this->assertEquals('0', $item->points_diff);
        });
        $this->assertEquals(5, $first[0]->points_diff);
        $this->assertEquals(4, $first[1]->points_diff);
        $this->assertEquals(3, $first[2]->points_diff);
        $this->assertEquals(1, $mid[0]->points_diff);
    }

    /** @test */
    public function it_only_adds_positive_points_diff()
    {
        $items = collect([]);

        $items->push(createLeaderboardItem(6, '6', '1'));
        $items->push(createLeaderboardItem(5, '5', '2'));
        $items->push(createLeaderboardItem(4, '4', '3'));

        $query = new LeaderboardImpl();
        $query->initialize($items, 5);

        $first = $query->build()->get()->sections->first();

        $this->assertEquals(1, $first[0]->points_diff);
        $this->assertEquals('0', $first[1]->points_diff);
        $this->assertEquals('0', $first[2]->points_diff);
    }
}
