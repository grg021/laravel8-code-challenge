<?php

namespace App\Leaderboards\Pipeline;

use Illuminate\Support\Collection;

class BuildTopBuildSection extends BuildSection
{

    /**
     * @param  Collection  $rankItems
     * @param  int  $userKey
     * @return int
     */
    protected function getLimit(Collection $rankItems, int $userKey)
    {

        if ($rankItems->count() <= self::MAX_SIZE) {
            return $rankItems->count();
        }

        if ($userKey === 2 || $userKey === 3) {
            return $userKey + 2;
        }

        return self::MIN_SIZE;
    }
}
