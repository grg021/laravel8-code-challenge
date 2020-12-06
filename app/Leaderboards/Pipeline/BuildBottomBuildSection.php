<?php

namespace App\Leaderboards\Pipeline;

use App\Leaderboards\Leaderboard;

class BuildBottomBuildSection extends BuildSection
{

    /**
     * @param  Leaderboard  $content
     * @return Leaderboard
     */
    public function build(Leaderboard $content): Leaderboard
    {

        $maxSize = self::MAX_SIZE - $content->sections->first()->count();
        $rankItems = $content->rankItems->reverse()->values();
        $size = $this->determineSizeForSection($rankItems, $content->userId, $maxSize);
        $content->sectionItems = $rankItems->take($size)->reverse()->values();
        $content->rankItems = $this->removeItemsFromList($content->sectionItems, $rankItems)->reverse()->values();

        return $content;
    }

}
