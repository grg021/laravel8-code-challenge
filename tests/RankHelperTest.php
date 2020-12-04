<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class RankHelperTest extends TestCase
{

    /** @test */
    public function it_adds_rank_to_the_items()
    {
        $data = collect([
            (object) [
                'points' => 10,
                'user_id' => 1
            ],
            (object) [
                'points' => 9,
                'user_id' => 2
            ],
        ]);

        $expectedData = collect([
            (object) [
                'points' => 10,
                'user_id' => 1,
                'rank' => 1
            ],
            (object) [
                'points' => 9,
                'user_id' => 2,
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
                'user_id' => 1,
            ],
            (object) [
                'points' => 9,
                'user_id' => 2
            ],
            (object) [
                'points' => 9,
                'user_id' => 3
            ],
            (object) [
                'points' => 5,
                'user_id' => 4
            ],
        ]);

        $expectedData = collect([
            (object) [
                'points' => 10,
                'user_id' => 1,
                'rank' => 1
            ],
            (object) [
                'points' => 9,
                'user_id' => 2,
                'rank' => 2
            ],
            (object) [
                'points' => 9,
                'user_id' => 3,
                'rank' => 2
            ],
            (object) [
                'points' => 5,
                'user_id' => 4,
                'rank' => 3
            ],
        ]);

        $this->assertEquals($expectedData, rank($data));
    }
}
