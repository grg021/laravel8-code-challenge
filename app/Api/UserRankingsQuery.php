<?php


namespace App\Api;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserRankingsQuery implements UserRankingsInterface
{
    private $query;

    public function __construct()
    {
        $this->query = DB::table('quiz_answers')
            ->join('quizzes', 'quiz_answers.quiz_id', '=', 'quizzes.id')
            ->join('lessons', 'quizzes.lesson_id', '=', 'lessons.id')
            ->join('courses', 'lessons.course_id', '=', 'courses.id')
            ->join('users', 'quiz_answers.user_id', '=', 'users.id');
    }


    public function course($courseId)
    {
        $this->query->where('courses.id', $courseId);
        return $this;
    }

    public function country($countryCode)
    {
        $this->query->where('users.country_code', $countryCode);
        return $this;
    }

    public function get(): Collection
    {
        return rank($this->query
            ->selectRaw('sum(score) as points, quiz_answers.user_id, 0 as highlight')
            ->groupBy('quiz_answers.user_id')
            ->orderBy('points', 'desc')
            ->get());
    }


}
