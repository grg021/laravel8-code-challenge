<?php

namespace Tests\integration;

use App\Api\UserRankingsBuilder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRankingsBuilderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @dataProvider itemsAndUserId
     */
    public function it_returns_2_sections_if_user_is_in_top_or_bottom_4($items, $userId)
    {
        $query = new UserRankingsBuilder($items, $userId);
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

        $query = new UserRankingsBuilder($items, 10);

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

        $query = new UserRankingsBuilder($items, 1);
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
                'highlight' => 0
            ]);
        }

        $query = new UserRankingsBuilder($items, 1);
        $actual = $query->build()->get();

        $this->assertSameSize($items, $actual->first());
        $this->assertEquals(1, $actual->first()[0]->highlight);
        $this->assertEquals(0, $actual->first()[1]->highlight);
    }
}
