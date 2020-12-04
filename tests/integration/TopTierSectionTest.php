<?php

namespace Tests\integration;

use App\Models\Course;
use App\Models\User;
use App\Api\UserRankingsBuilder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TopTierSectionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_all_data_if_total_size_is_less_than_or_equal_size()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        User::factory()->count(3)->create();

        $query = new UserRankingsBuilder($course);

        $this->assertEquals(collect([]), $query->topTier()->getSectionItems());

        $this->makeQuizAnswer(1, $quiz, 1);

        $expectedValues = [
            ['1', '1'],
        ];

        $query = new UserRankingsBuilder($course);
        $this->checkValues($query->topTier()->getSectionItems(), $expectedValues);

        $this->makeQuizAnswer(2, $quiz, 5);

        $expectedValues = [
            ['2', '5'],
            ['1', '1'],
        ];
        $query = new UserRankingsBuilder($course);
        $this->checkValues($query->topTier()->getSectionItems(), $expectedValues);

        $this->makeQuizAnswer(3, $quiz, 7);

        $expectedValues = [
            ['3', '7'],
            ['2', '5'],
            ['1', '1'],
        ];
        $query = new UserRankingsBuilder($course);
        $this->checkValues($query->topTier()->getSectionItems(), $expectedValues);
    }

    /** @test */
    public function it_returns_four_items_if_logged_in_user_is_ranked_3rd()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        $user = User::factory()->create();
        auth()->login($user);
        User::factory()->count(4)->create();

        $this->makeQuizAnswer(1, $quiz, 7);
        $this->makeQuizAnswer(2, $quiz, 5);
        $this->makeQuizAnswer(3, $quiz, 1);
        $this->makeQuizAnswer(4, $quiz, 9);
        $this->makeQuizAnswer(5, $quiz, 8);

        $query = new UserRankingsBuilder($course);

        $expectedValues = [
            ['4', '9'],
            ['5', '8'],
            ['1', '7'],
            ['2', '5'],
        ];

        $actual = $query->topTier()->getSectionItems();

        $this->assertCount(4, $actual);

        $this->checkValues($actual, $expectedValues);
    }

    /** @test */
    public function it_returns_five_items_if_logged_in_user_is_ranked_4th()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        $user = User::factory()->create();
        auth()->login($user);
        User::factory()->count(5)->create();

        $this->makeQuizAnswer(1, $quiz, 6);
        $this->makeQuizAnswer(2, $quiz, 5);
        $this->makeQuizAnswer(3, $quiz, 1);
        $this->makeQuizAnswer(4, $quiz, 9);
        $this->makeQuizAnswer(5, $quiz, 8);
        $this->makeQuizAnswer(6, $quiz, 7);

        $query = new UserRankingsBuilder($course);

        $expectedValues = [
            ['4', '9'],
            ['5', '8'],
            ['6', '7'],
            ['1', '6'],
            ['2', '5'],
        ];

        $actual = $query->topTier()->getSectionItems();

        $this->assertSameSize($expectedValues, $actual);

        $this->checkValues($actual, $expectedValues);
    }

    /** @test */
    public function it_returns_three_items_if_logged_in_user_is_beyond_top_tier_level()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        $user = User::factory()->create();
        auth()->login($user);
        User::factory()->count(5)->create();

        $this->makeQuizAnswer(2, $quiz, 6);
        $this->makeQuizAnswer(1, $quiz, 5);
        $this->makeQuizAnswer(3, $quiz, 1);
        $this->makeQuizAnswer(4, $quiz, 9);
        $this->makeQuizAnswer(5, $quiz, 8);
        $this->makeQuizAnswer(6, $quiz, 7);

        $query = new UserRankingsBuilder($course);

        $expectedValues = [
            ['4', '9'],
            ['5', '8'],
            ['6', '7'],
        ];

        $actual = $query->topTier()->getSectionItems();

        $this->assertSameSize($expectedValues, $actual);

        $this->checkValues($actual, $expectedValues);
    }
}
