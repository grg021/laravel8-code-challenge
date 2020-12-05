<?php

namespace Tests\RankingsTests;

use App\Models\Course;
use App\Models\User;
use App\UserRankings\UserRankingsBuilder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TopTierSectionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_1_section_if_total_data_is_less_than_9()
    {
        $items = collect([]);

        foreach (range(9, 1) as $key => $item) {
            $items->push(createRankItemObject($item, '1', '1'));
        }

        $query = new UserRankingsBuilder();
        $query->initialize($items, 1);
        $sections = $query->build()->get();


        $this->assertCount(1, $sections);
    }

    /** @test */
    public function it_returns_all_data_if_total_size_is_less_than_or_equal_minimum_size()
    {

        $items = collect([]);

        $items->push(createRankItemObject(6, '6', '1'));

        $query = new UserRankingsBuilder();
        $query->initialize($items, 6);
        $sections = $query->build()->get();

        $this->assertCount(1, $sections);
        $this->assertCount(1, $sections->first());

        $items->push(createRankItemObject(5, '5', '2'));
        $query->initialize($items, 6);
        $sections = $query->build()->get();

        $this->assertCount(1, $sections);
        $this->assertCount(2, $sections->first());

        $items->push(createRankItemObject(4, '4', '3'));
        $query->initialize($items, 6);
        $sections = $query->build()->get();

        $this->assertCount(1, $sections);
        $this->assertCount(3, $sections->first());
        $items->push(createRankItemObject(4, '4', '3'));

    }

}