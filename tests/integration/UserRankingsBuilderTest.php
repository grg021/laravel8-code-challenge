<?php

namespace Tests\integration;

use App\Api\UserRankingsBuilder;
use App\Models\Course;
use App\Models\RankItem;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRankingsBuilderTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function it_returns_3_sections()
    {
        $course = Course::factory()->create();
        $quiz = $this->makeQuiz($course);

        $userListTop = User::factory()->count(10)->create();
        $loggedInUser = User::factory()->create();
        $userListBottom = User::factory()->count(10)->create();

        auth()->login($loggedInUser);
        $score = 0;
        foreach ($userListTop as $user) {
            $this->makeQuizAnswer($user->id, $quiz, $score += 1);
        }
        $this->makeQuizAnswer($loggedInUser->id, $quiz, $score += 1);
        foreach ($userListBottom as $user) {
            $this->makeQuizAnswer($user->id, $quiz, $score += 1);
        }


        $query = new UserRankingsBuilder($course);

        $actual = $query->build()->get();

        $this->assertEquals(3, $actual->count());

    }

}
