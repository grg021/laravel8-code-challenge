<?php

namespace App\UserRankings\Pipeline;

use App\UserRankings\Popo\BuildSectionContent;

class BuildTopBuildSection extends BuildSection
{

    /**
     * @param  BuildSectionContent  $content
     * @return BuildSectionContent
     */
    public function build(BuildSectionContent $content): BuildSectionContent
    {
        $size = $this->determineSizeForSection($content->rankItems, $content->userId, self::MAX_SIZE);
        $sectionItems = $content->rankItems->take($size)->values();
        $content->rankItems = $this->removeItemsFromList($sectionItems, $content->rankItems);
        $content->sections->push($sectionItems);

        return $content;
    }
}
