<?php

namespace App\Leaderboards\Pipeline;

use App\Leaderboards\Leaderboard;

class BuildMiddleBuildSection extends BuildSection
{

    /**
     * @param  Leaderboard  $content
     * @return Leaderboard
     */
    public function build(Leaderboard $content): Leaderboard
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
        $content->sections = $content->sections->filter(function ($section) {
            return $section->count();
        });

        return $content;
    }
}
