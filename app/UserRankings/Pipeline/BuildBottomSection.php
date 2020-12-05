<?php

namespace App\UserRankings\Pipeline;

use Closure;

class BuildBottomSection extends Section
{
    public function handle($content, Closure $next)
    {
        // TODO use pojo
        $rankItems = $content[0];
        $userId = $content[1];
        $sections = $content[2];

        $maxSize = self::MAX_SIZE - $sections->first()->count();

        $rankItems = $rankItems->reverse()->values();

        $size = $this->determineSizeForSection($rankItems, $userId, $maxSize);

        $sectionItems = $rankItems->take($size)->sortBy('rank')->values();

        $rankItems = $this->removeItemsFromList($sectionItems, $rankItems)->reverse()->values();

        return $next([$rankItems, $userId, $sections, $sectionItems]);
    }
}
