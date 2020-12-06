<?php

namespace Tests;

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function makeQuizAnswer($userId, $quiz, $score)
    {
        return QuizAnswer::factory()->state([
            'quiz_id' => $quiz->id,
            'user_id' => $userId,
            'score' => $score,
        ])->create();
    }

    public function makeQuiz($course)
    {
        $lesson = Lesson::factory()->state([
            'course_id' => $course->getKey(),
        ])->create();
        return Quiz::factory()->state([
            'lesson_id' => $lesson->getKey(),
            'max_score' => 10,
        ])->create();
    }

    /**
     * @param $values
     * @param  array  $expectedValues
     */
    public function checkValues($actual, array $expectedValues): void
    {
        foreach($expectedValues as $key => $expected) {
            $this->assertEquals($expected[0], $actual[$key]->userId);
            $this->assertEquals($expected[1], $actual[$key]->points);
        }
    }

}
