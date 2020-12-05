<?php

namespace Tests\integration;

use App\Models\Country;
use App\Models\Course;
use App\Models\User;
use App\Api\UserRankingsQuery;
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

        $query = new UserRankingsQuery();
        $query->course($course->id);

        $expectedValues = [
            ['3', '7'],
            ['2', '5'],
            ['1', '1'],
        ];

        $this->checkValues($query->get(), $expectedValues);
    }

    /** @test */
    public function it_can_be_filtered_by_counrtry_code_of_user()
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

        $query = new UserRankingsQuery();
        $query->country($countryCodes[1]);

        $expectedValues = [
            ['6', '7'],
            ['5', '5'],
            ['4', '1'],
        ];
        $this->assertEquals(3, $query->get()->count());
        $this->checkValues($query->get(), $expectedValues);
    }

}
