<?php

namespace Tests\integration;

use App\Api\UserRankingsBuilder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRankingsBuilderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_always_displays_top_3_bottom_3()
    {
        $items = collect([]);

        foreach (range(9, 1) as $key => $item) {
            $items->push((object) [
                'points' => '1',
                'user_id' => $item,
                'rank' => 1,
            ]);
        }

        $query = new UserRankingsBuilder();
        $query->initialize($items, 1);
        $sections = $query->build()->get();

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
        $query = new UserRankingsBuilder();
        $query->initialize($items, $userId);
        $this->assertCount(2, $query->build()->get());
    }

    public function itemsAndUserId()
    {
        $items = collect([]);

        foreach (range(21, 1) as $key => $item) {
            $items->push((object) [
                'points' => $item,
                'user_id' => $item,
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
                'user_id' => $item,
                'rank' => $key + 1,
            ]);
        }

        $query = new UserRankingsBuilder();
        $query->initialize($items, 10);

        $actual = $query->build()->get();

        $this->assertEquals(3, $actual->count());
    }


    /** @test */
    public function it_puts_higher_priority_for_logged_in_user_when_score_is_the_same()
    {
        $items = collect([]);

        foreach (range(3, 1) as $key => $item) {
            $items->push((object) [
                'points' => '1',
                'user_id' => $item,
                'rank' => 1,
            ]);
        }

        $query = new UserRankingsBuilder();
        $query->initialize($items, 1);
        $actual = $query->build()->get();

        $this->assertEquals(1, $actual->first()->first()->user_id);
    }

    /** @test */
    public function it_highlights_logged_in_user()
    {
        $items = collect([]);

        foreach (range(2, 1) as $key => $item) {
            $items->push((object) [
                'points' => '1',
                'user_id' => $item,
                'rank' => 1,
                'highlight' => 0,
                'points_diff' => '0'
            ]);
        }

        $query = new UserRankingsBuilder();
        $query->initialize($items, 1);
        $actual = $query->build()->get();

        $this->assertSameSize($items, $actual->first());
        $this->assertEquals(1, $actual->first()[0]->highlight);
        $this->assertEquals(0, $actual->first()[1]->highlight);
    }

    /** @test */
    public function it_only_points_diff_on_rank_items_on_same_section_as_user()
    {
        $items = collect([]);

        $items->push(createRankItemObject(6, '6', '1'));
        $items->push(createRankItemObject(5, '5', '2'));
        $items->push(createRankItemObject(4, '4', '3'));
        $items->push(createRankItemObject(3, '3', '4'));
        $items->push(createRankItemObject(2, '2', '5'));
        $items->push(createRankItemObject(1, '1', '6'));

        $query = new UserRankingsBuilder();
        $query->initialize($items, 4);
        $sections = $query->build()->get();

        $first = $sections->first();
        $last = $sections->last();

        $this->assertEquals(2, $first[0]->points_diff);
        $this->assertEquals(1, $first[1]->points_diff);
        $this->assertEquals('0', $first[2]->points_diff);
        $this->assertEquals('0', $first[3]->points_diff);
        $this->assertEquals('0', $last[0]->points_diff);
        $this->assertEquals('0', $last[1]->points_diff);
    }

    /** @test */
    public function it_only_adds_positive_points_diff()
    {
        $items = collect([]);

        $items->push(createRankItemObject(6, '6', '1'));
        $items->push(createRankItemObject(5, '5', '2'));
        $items->push(createRankItemObject(4, '4', '3'));

        $query = new UserRankingsBuilder();
        $query->initialize($items, 5);

        $first = $query->build()->get()->first();

        $this->assertEquals(1, $first[0]->points_diff);
        $this->assertEquals('0', $first[1]->points_diff);
        $this->assertEquals('0', $first[2]->points_diff);
    }

    /** @test */
    public function it_returns_rank_of_user_in_rankings()
    {
        $items = collect([]);

        $items->push(createRankItemObject(3, '6', '1'));
        $items->push(createRankItemObject(2, '5', '2'));
        $items->push(createRankItemObject(1, '4', '3'));

        $query = new UserRankingsBuilder();
        $query->initialize($items, 1);

        $this->assertEquals('3rd', $query->getUserRank());
    }
}
