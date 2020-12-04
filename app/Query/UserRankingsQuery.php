<?php


namespace App\Query;


use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserRankingsQuery
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

    public function query(): Builder
    {
        return $this->query;
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

    public function count()
    {
        return $this->query->get()->count();
    }

    public function get(): Collection
    {
        return rank($this->query
            ->selectRaw('sum(score) as points, quiz_answers.user_id')
            ->groupBy('quiz_answers.user_id')
            ->orderBy('points', 'desc')
            ->get());
    }


}
