<?php

namespace App\UserRankings\Pipeline;

use App\UserRankings\Popo\BuildSectionContent;

class BuildMiddleBuildSection extends BuildSection
{

    /**
     * @param  BuildSectionContent  $content
     * @return BuildSectionContent
     */
    public function build(BuildSectionContent $content): BuildSectionContent
    {
        $middle = collect([]);
        $pos = getUserPosition($content->rankItems, $content->userId);

        if ($pos > -1) {
            $middle->push($content->rankItems[$pos-1]);
            $middle->push($content->rankItems[$pos]);
            $middle->push($content->rankItems[$pos+1]);
        }

        $content->sections->push($middle);
        $content->sections->push($content->sectionItems);

        $content->sections->filter(function ($section) {
            return $section->count();
        });

        return $content;
    }
}
