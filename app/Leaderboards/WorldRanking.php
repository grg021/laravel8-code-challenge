<?php

namespace App\Leaderboards;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WorldRanking implements CourseRankings
{

    /**
     * @var Builder
     */
    protected Builder $query;

    /**
     * WorldRanking constructor.
     * @param $courseId
     */
    public function __construct($courseId)
    {
        $this->query = DB::table('quiz_answers')
            ->join('quizzes', 'quiz_answers.quiz_id', '=', 'quizzes.id')
            ->join('lessons', 'quizzes.lesson_id', '=', 'lessons.id')
            ->join('users', 'quiz_answers.user_id', '=', 'users.id')
            ->where('lessons.course_id', '=', $courseId);
    }

    /**
     * @return Builder
     */
    protected function filter(): Builder
    {
        return $this->query;
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->filter()
            ->selectRaw('users.name, sum(score) as points, quiz_answers.user_id')
            ->groupBy('quiz_answers.user_id')
            ->orderByDesc('points')
            ->orderBy('name')
            ->get();
    }
}
