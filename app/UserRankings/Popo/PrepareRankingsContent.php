<?php


namespace App\UserRankings\Popo;


use Illuminate\Support\Collection;

class PrepareRankingsContent
{
    public Collection $rankItems;
    public int $userId;

    public function __construct(Collection $rankItems, int $userId)
    {
        $this->rankItems = $rankItems;
        $this->userId = $userId;
    }
}
