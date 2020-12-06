<?php

namespace App\Leaderboards\Pipeline;

use App\Leaderboards\Leaderboard;

class BuildTopBuildSection extends BuildSection
{

    /**
     * @param  Leaderboard  $content
     * @return Leaderboard
     */
    public function build(Leaderboard $content): Leaderboard
    {
        $size = $this->determineSizeForSection($content->rankItems, $content->userId, self::MAX_SIZE);
        $sectionItems = $content->rankItems->take($size)->values();
        $content->rankItems = $this->removeItemsFromList($sectionItems, $content->rankItems);
        $content->sections->push($sectionItems);

        return $content;
    }
}
