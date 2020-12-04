<?php


namespace App\Models;


use App\Api\UserRankingsBuilder;

class RankingsBottomSection extends Section
{
    public function __construct(UserRankingsBuilder $userRankings)
    {
        parent::__construct($userRankings);
        $this->items = $userRankings->bottomTier()->list()->mapInto(RankItem::class);
    }
}
