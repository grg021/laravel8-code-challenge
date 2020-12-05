<?php

namespace App\UserRankings\Pipeline;

use App\UserRankings\Popo\BuildSectionContent;

class BuildBottomSection extends Section
{

    /**
     * @param  BuildSectionContent  $content
     * @return BuildSectionContent
     */
    public function build(BuildSectionContent $content): BuildSectionContent
    {
        $maxSize = self::MAX_SIZE - $content->sections->first()->count();
        $rankItems = $content->rankItems->reverse()->values();
        $size = $this->determineSizeForSection($rankItems, $content->userId, $maxSize);
        $content->sectionItems = $rankItems->take($size)->sortBy('rank')->values();
        $content->rankItems = $this->removeItemsFromList($content->sectionItems, $rankItems)->reverse()->values();

        return $content;
    }

}
