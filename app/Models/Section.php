<?php


namespace App\Models;


use App\Api\UserRankingsBuilder;
use Illuminate\Support\Collection;

class Section extends Collection
{

    public function __construct(UserRankingsBuilder $userRankings)
    {
        $this->items = [];
    }

}
