<?php


namespace App\UserRankings\Pipeline;


use Closure;

class BuildTopSection extends Section
{

    public function handle($content, Closure $next)
    {
        // TODO use pojo
        $rankItems = $content[0];
        $userId = $content[1];
        $sections = $content[2];

        $size = $this->determineSizeForSection($rankItems, $userId, self::MAX_SIZE);

        $sectionItems = $rankItems->take($size)->values();

        $rankItems = $this->removeItemsFromList($sectionItems, $rankItems);
        $sections->push($sectionItems);

        return $next([$rankItems, $userId, $sections]);
    }

}
