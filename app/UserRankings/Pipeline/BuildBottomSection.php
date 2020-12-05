<?php

namespace App\UserRankings\Pipeline;

use Closure;

class BuildBottomSection extends Section
{
    public function handle($content, Closure $next)
    {
        $list = $content[0];
        $userId = $content[1];
        $sections = $content[2];

        $maxSize = self::MAX_SIZE - $sections->first()->count();

        $list = $list->reverse()->values();

        $size = self::MIN_SIZE;

        $size = ($list->count() <= $maxSize)
            ? $maxSize
            : $this->getSizeBasedOnUserPosition($size, $list, $userId);

        $sectionItems = $list->take($size)->sortBy('rank')->values();

        $list = $this->removeItemsFromList($sectionItems, $list)->reverse()->values();

        return $next([$list, $userId, $sections, $sectionItems]);
    }
}
