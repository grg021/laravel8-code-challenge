<?php

namespace Tests\RankingsTests;

use Tests\TestCase;

class RankHelperTest extends TestCase
{

    /** @test */
    public function it_adds_rank_to_the_items()
    {
        $data = collect([
            (object) [
                'points' => 10,
                'userId' => 1
            ],
            (object) [
                'points' => 9,
                'userId' => 2
            ],
        ]);

        $expectedData = collect([
            (object) [
                'points' => 10,
                'userId' => 1,
                'rank' => 1
            ],
            (object) [
                'points' => 9,
                'userId' => 2,
                'rank' => 2
            ],
        ]);

        $this->assertEquals($expectedData, rank($data));
    }


    /** @test */
    public function it_adds_ranks_same_points()
    {
        $data = collect([
            (object) [
                'points' => 10,
                'userId' => 1,
            ],
            (object) [
                'points' => 9,
                'userId' => 2
            ],
            (object) [
                'points' => 9,
                'userId' => 3
            ],
            (object) [
                'points' => 5,
                'userId' => 4
            ],
        ]);

        $expectedData = collect([
            (object) [
                'points' => 10,
                'userId' => 1,
                'rank' => 1
            ],
            (object) [
                'points' => 9,
                'userId' => 2,
                'rank' => 2
            ],
            (object) [
                'points' => 9,
                'userId' => 3,
                'rank' => 2
            ],
            (object) [
                'points' => 5,
                'userId' => 4,
                'rank' => 3
            ],
        ]);

        $this->assertEquals($expectedData, rank($data));
    }

    /** @test */
    public function it_converts_rank_into_string()
    {
        $this->assertEquals('1st', ordinal(1));
        $this->assertEquals('2nd', ordinal(2));
        $this->assertEquals('3rd', ordinal(3));
        $this->assertEquals('4th', ordinal(4));
        $this->assertEquals('21st', ordinal(21));
        $this->assertEquals('32nd', ordinal(32));
        $this->assertEquals('43rd', ordinal(43));
        $this->assertEquals('100th', ordinal(100));
    }

    /** @test */
    public function it_returns_user_rank()
    {
        $items = collect([]);

        $items->push(createLeaderboardItem(3, '6', '1'));
        $items->push(createLeaderboardItem(2, '5', '2'));
        $items->push(createLeaderboardItem(1, '4', '3'));

        $this->assertEquals('3rd', getUserRank($items, 1));
    }
}
