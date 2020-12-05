<?php

namespace Tests\integration;

use App\UserRankings\UserRankingsBuilder;
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
            $items->push(createRankItemObject($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new UserRankingsBuilder();
        $query->initialize($items, 1);
        $sections = $query->build()->get();

        $this->assertCount(2, $sections);
        $section = $sections->last();

        $this->assertEquals('3', $section[0]->user_id);
        $this->assertEquals('2', $section[1]->user_id);
        $this->assertEquals('1', $section[2]->user_id);

    }

    /** @test */
    public function it_returns_bottom_four_from_list_if_user_is_3rd_to_last()
    {
        $items = collect([]);

        foreach (range(10, 1) as $n) {
            $items->push(createRankItemObject($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new UserRankingsBuilder();
        $query->initialize($items, 3);
        $sections = $query->build()->get();

        $this->assertCount(2, $sections);
        $section = $sections->last();
        $this->assertCount(4, $section);


        $this->assertEquals('4', $section[0]->user_id);
        $this->assertEquals('3', $section[1]->user_id);
        $this->assertEquals('2', $section[2]->user_id);
        $this->assertEquals('1', $section[3]->user_id);
    }

    /** @test */
    public function it_returns_bottom_five_from_list_if_user_is_4th_to_last()
    {
        $items = collect([]);

        foreach (range(10, 1) as $n) {
            $items->push(createRankItemObject($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new UserRankingsBuilder();
        $query->initialize($items, 4);
        $sections = $query->build()->get();

        $this->assertCount(2, $sections);
        $section = $sections->last();
        $this->assertCount(5, $section);


        $this->assertEquals('5', $section[0]->user_id);
        $this->assertEquals('4', $section[1]->user_id);
        $this->assertEquals('3', $section[2]->user_id);
        $this->assertEquals('2', $section[3]->user_id);
        $this->assertEquals('1', $section[4]->user_id);
    }


}
