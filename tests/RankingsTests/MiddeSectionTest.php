<?php

namespace Tests\RankingsTests;

use App\UserRankings\UserRankingsBuilder;
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
            $items->push(createRankItemObject($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new UserRankingsBuilder();
        $query->initialize($items, 5);
        $sections = $query->build()->get();


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
            $items->push(createRankItemObject($n, $n, '1'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new UserRankingsBuilder();
        $query->initialize($items, 5);
        $sections = $query->build()->get();
        $this->assertCount(3, $sections[1]);
        $this->assertEquals(5, $sections[1][1]->user_id);
    }

    /** @test */
    public function it_returns_middle_section_with_user_padding_if_any()
    {
        $items = collect([]);

        foreach (range(12, 8) as $n) {
            $items->push(createRankItemObject($n, 3, '1'));
        }

        foreach (range(7, 5) as $n) {
            $items->push(createRankItemObject($n, 2, '2'));
        }

        foreach (range(4, 1) as $n) {
            $items->push(createRankItemObject($n, 1, '3'));
        }

        $items = rank($items->sortByDesc('points')->values());

        $query = new UserRankingsBuilder();
        $query->initialize($items, 6);
        $sections = $query->build()->get();

        $this->assertCount(3, $sections);
        $this->assertCount(3, $sections[1]);
        $this->assertEquals(6, $sections[1][1]->user_id);
    }

}
