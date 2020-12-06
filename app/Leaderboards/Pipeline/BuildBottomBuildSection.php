<?php

namespace App\Leaderboards\Pipeline;

use Illuminate\Support\Collection;

class BuildBottomBuildSection extends BuildSection
{

    /**
     * @param  Collection  $rankItems
     * @param  int  $userKey
     * @return int|mixed
     */
    protected function getIndex(Collection $rankItems, int $userKey)
    {

        if ($rankItems->count() <= self::MAX_SIZE) {
            return $rankItems->count();
        }

        $lastKey = $rankItems->keys()->last();

        if ($lastKey - 2  === $userKey || $lastKey - 3 === $userKey) {
            return $userKey - 1;
        }

        return $rankItems->count() - self::MIN_SIZE;
    }
}
