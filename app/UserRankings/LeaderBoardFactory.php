<?php


namespace App\UserRankings;


use App\Models\RankItem;

class LeaderBoardFactory
{

    /**
     * @var RankingsBuilderInterface
     */
    private RankingsBuilderInterface $builder;

    public function __construct(RankingsBuilderInterface $builder)
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
