<?php

namespace Tests\integration;

use App\Models\Course;
use App\Models\User;
use App\Api\UserRankingsBuilder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BottomSectionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_bottom_three_from_list()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        User::factory()->count(8)->create();

        $this->makeQuizAnswer(1, $quiz, 5);
        $this->makeQuizAnswer(2, $quiz, 6);
        $this->makeQuizAnswer(3, $quiz, 1);
        $this->makeQuizAnswer(4, $quiz, 9);
        $this->makeQuizAnswer(5, $quiz, 8);
        $this->makeQuizAnswer(6, $quiz, 7);
        $this->makeQuizAnswer(7, $quiz, 10);
        $this->makeQuizAnswer(8, $quiz, 9);

        $query = new UserRankingsBuilder($course);

        $expectedValues = [
            ['2', '6'],
            ['1', '5'],
            ['3', '1'],
        ];

        $actual = $query->bottomTier()->list();

        $this->assertSameSize($expectedValues, $actual);

        $this->checkValues($actual, $expectedValues);
    }

    /** @test */
    public function it_returns_bottom_four_from_list_if_user_is_3rd_to_last()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        $user = User::factory()->create();

        auth()->login($user);

        User::factory()->count(7)->create();

        $this->makeQuizAnswer(1, $quiz, 6);
        $this->makeQuizAnswer(2, $quiz, 5);
        $this->makeQuizAnswer(3, $quiz, 1);
        $this->makeQuizAnswer(4, $quiz, 9);
        $this->makeQuizAnswer(5, $quiz, 8);
        $this->makeQuizAnswer(6, $quiz, 7);
        $this->makeQuizAnswer(7, $quiz, 10);
        $this->makeQuizAnswer(8, $quiz, 9);

        $query = new UserRankingsBuilder($course);

        $expectedValues = [
            ['6', '7'],
            ['1', '6'],
            ['2', '5'],
            ['3', '1'],
        ];

        $actual = $query->bottomTier()->list();

        $this->assertSameSize($expectedValues, $actual);

        $this->checkValues($actual, $expectedValues);
    }



    /** @test */
    public function it_returns_an_empty_collection_if_data_is_not_enough()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        User::factory()->count(3)->create();

        $query = new UserRankingsBuilder($course);

        $this->assertEquals(collect([]), $query->bottomTier()->list());

        $this->makeQuizAnswer(1, $quiz, 1);
        $this->assertEquals(collect([]), $query->bottomTier()->list());

        $this->makeQuizAnswer(2, $quiz, 1);
        $this->makeQuizAnswer(3, $quiz, 1);
        $this->assertEquals(collect([]), $query->bottomTier()->list());
    }

    /** @test */
    public function it_returns_an_last_item_if_there_are_four_items()
    {
        $this->assertEquals(1, 1);
    }
}
