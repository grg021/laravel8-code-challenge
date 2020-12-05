<?php

namespace App\UserRankings;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RankingsQuery implements RankingsQueryInterface
{
    private Builder $query;
    private Collection $rankings;

    public function __construct()
    {
        $this->query = DB::table('quiz_answers')
            ->join('quizzes', 'quiz_answers.quiz_id', '=', 'quizzes.id')
            ->join('lessons', 'quizzes.lesson_id', '=', 'lessons.id')
            ->join('users', 'quiz_answers.user_id', '=', 'users.id');
        $this->rankings = collect([]);
    }

    public function course($courseId): RankingsQueryInterface
    {
        $this->query->where('lessons.course_id', $courseId);
        return $this;
    }

    public function country($countryCode): RankingsQueryInterface
    {
        $this->query->where('users.country_code', $countryCode);
        return $this;
    }

    public function get(): Collection
    {
        $this->rankings = $this->query
            ->selectRaw('sum(score) as points, quiz_answers.user_id, 0 as highlight, 0 as points_diff ')
            ->groupBy('quiz_answers.user_id')
            ->orderByDesc('points')
            ->orderByDesc('name')
            ->get();

        return rank($this->rankings);
    }
}
