<?php

namespace Tests\integration;

use App\Api\UserRankingsBuilder;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MiddeSectionTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function it_returns_three_items_with_the_logged_in_user_in_the_middle()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        User::factory()->count(2)->create();
        $user = User::factory()->create();
        auth()->login($user);
        User::factory()->count(2)->create();

        $this->makeQuizAnswer(5, $quiz, 5);
        $this->makeQuizAnswer(1, $quiz, 2);
        $this->makeQuizAnswer(4, $quiz, 4);
        $this->makeQuizAnswer(3, $quiz, 3);
        $this->makeQuizAnswer(2, $quiz, 1);

        $query = new UserRankingsBuilder($course);

        $expectedValues = [
            ['4', '4'],
            ['3', '3'],
            ['1', '2'],
        ];

        $actual = $query->middleTier()->getSectionItems();

        $this->assertSameSize($expectedValues, $actual);

        $this->checkValues($actual, $expectedValues);
    }

}
