<?php


namespace App\UserRankings\Pipeline;


use Closure;

class BuildTopSection extends Section
{

    public function handle($content, Closure $next)
    {

        $list = $content[0];
        $userId = $content[1];
        $sections = $content[2];

        $size = self::MIN_SIZE;

        $size = ($list->count() <= self::MAX_SIZE)
            ? self::MAX_SIZE
            : $this->getSizeBasedOnUserPosition($size, $list, $userId);

        $sectionItems = $list->take($size)->values();

        $list = $this->removeItemsFromList($sectionItems, $list);
        $sections->push($sectionItems);

        return $next([$list, $userId, $sections]);
    }
}
