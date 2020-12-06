<?php

namespace App\Leaderboards;

use App\Models\RankItem;

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
     * @param $rankList
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getLeaderboard($rankList, $userId)
    {
        return $this->builder
            ->initialize($rankList, $userId)
            ->build()
            ->transform(RankItem::class)
            ->get();
    }
}
