<?php

namespace App\Leaderboards;

class LeaderBoardFactory
{

    /**
     * @var LeaderboardBuilder
     */
    private LeaderboardBuilder $builder;

    /**
     * LeaderBoardFactory constructor.
     * @param  LeaderboardBuilder  $builder
     */
    public function __construct(LeaderboardBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param  CourseRankings  $rankings
     * @param $userId
     * @return Leaderboard
     */
    public function getLeaderboard(CourseRankings $rankings, $userId): Leaderboard
    {
        $rankList = $rankings->get();
        return $this->builder
            ->initialize($rankList, $userId)
            ->build()
            ->get();
    }
}
