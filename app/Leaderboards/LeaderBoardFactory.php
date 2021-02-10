<?php

namespace App\Leaderboards;

use App\Models\Course;
use Illuminate\Contracts\Auth\Authenticatable;

class LeaderBoardFactory
{

    /**
     * @param  LeaderboardBuilder  $builder
     * @param  Authenticatable  $user
     * @param  CourseRankings  $rankings
     * @return Leaderboard
     */
    public static function getLeaderboard(
        LeaderboardBuilder $builder,
        Authenticatable $user,
        WorldRanking $rankings
    ): Leaderboard {
        $rankList = $rankings->get();
        $userId = ($user->getAuthIdentifier()) ? $user->getAuthIdentifier() : 0;
        return $builder
            ->initialize($rankList, $userId)
            ->build()
            ->get();
    }

}
