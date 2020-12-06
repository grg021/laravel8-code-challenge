<?php


namespace App\UserRankings;


use App\Models\RankItem;

class LeaderBoardFactory
{

    /**
     * @var LeaderboardBuilder
     */
    private LeaderboardBuilder $builder;

    public function __construct(LeaderboardBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function getLeaderboard($rankList, $userId)
    {
        return $this->builder
            ->initialize($rankList, $userId)
            ->build()
            ->transform(RankItem::class)
            ->get();
    }

}
