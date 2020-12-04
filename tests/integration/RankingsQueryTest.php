<?php

namespace Tests\integration;

use App\Models\Country;
use App\Models\Course;
use App\Models\User;
use App\Query\UserRankSections;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RankingsQueryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_a_collection_of_userid_and_points_of_a_course_sorted_by_points()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        User::factory()->count(3)->create();

        $this->makeQuizAnswer(1, $quiz, 1);
        $this->makeQuizAnswer(3, $quiz, 7);
        $this->makeQuizAnswer(2, $quiz, 5);

        $query = new UserRankSections($course);

        $expectedValues = [
            ['3', '7'],
            ['2', '5'],
            ['1', '1'],
        ];

        $this->checkValues($query->list(), $expectedValues);
    }

    /** @test */
    public function it_returns_a_collection_of_userid_and_points_of_a_course_by_country_sorted_by_points()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);
        $countryCodes = Country::factory()->count(2)->create()->pluck('code');

        $users = User::factory()->count(3)->create([
            'country_code' => $countryCodes[0]
        ]);
        foreach ($users as $user) {
            $this->makeQuizAnswer($user->id, $quiz, 1);
        }

        User::factory()->count(3)->create([
            'country_code' => $countryCodes[1]
        ]);

        $this->makeQuizAnswer(4, $quiz, 1);
        $this->makeQuizAnswer(6, $quiz, 7);
        $this->makeQuizAnswer(5, $quiz, 5);

        $query = new UserRankSections($course, $countryCodes[1]);

        $expectedValues = [
            ['6', '7'],
            ['5', '5'],
            ['4', '1'],
        ];
        $this->assertSameSize($expectedValues, $query->list());
        $this->checkValues($query->list(), $expectedValues);
    }

    /** @test */
    public function it_puts_higher_priority_for_logged_in_user_when_score_is_the_same()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        $user = User::factory()->create();
        auth()->login($user);
        User::factory()->count(4)->create();

        $this->makeQuizAnswer(3, $quiz, 1);
        $this->makeQuizAnswer(2, $quiz, 7);
        $this->makeQuizAnswer(4, $quiz, 7);
        $this->makeQuizAnswer(1, $quiz, 7);
        $this->makeQuizAnswer(5, $quiz, 10);

        $query = new UserRankSections($course);

        $expectedValues = [
            ['5', '10'],
            ['1', '7']
        ];

        $this->checkValues($query->topTier()->list(), $expectedValues);

        $query = new UserRankSections($course);

        $this->checkValues($query->bottomTier()->list(), $expectedValues);
    }
}
