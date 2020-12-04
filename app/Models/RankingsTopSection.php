<?php


namespace App\Models;


use App\Api\UserRankingsBuilder;

class RankingsTopSection extends Section
{
    public function __construct(UserRankingsBuilder $userRankings)
    {
        parent::__construct($userRankings);
        $this->items = $userRankings->topTier()->list()->mapInto(RankItem::class);
    }
}
