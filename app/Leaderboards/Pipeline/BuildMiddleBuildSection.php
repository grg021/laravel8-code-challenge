<?php

namespace App\Leaderboards\Pipeline;

use Illuminate\Support\Collection;

class BuildMiddleBuildSection extends BuildSection
{

    /**
     * @param  Collection  $rankItems
     * @param  int  $userKey
     * @return int
     */
    protected function getIndex(Collection $rankItems, int $userKey)
    {
        return ($userKey > 4 && $userKey < $rankItems->count() - 4) ? $userKey - 1 : 0;
    }

    /**
     * @param  Collection  $rankItems
     * @param  int  $userKey
     * @return int
     */
    protected function getLimit(Collection $rankItems, int $userKey)
    {
        return ($userKey > 4 && $userKey < $rankItems->count() - 4) ? self::MIN_SIZE : 0;
    }
}
