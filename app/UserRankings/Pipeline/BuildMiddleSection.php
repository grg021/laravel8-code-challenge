<?php

namespace App\UserRankings\Pipeline;

use Closure;

class BuildMiddleSection extends Section
{
    public function handle($content, Closure $next)
    {
        // TODO use pojo
        $rankItems = $content[0];
        $userId = $content[1];
        $sections = $content[2];
        $bottomList = $content[3];

        $middle = collect([]);
        $pos = getUserPosition($rankItems, $userId);
        if ($pos > -1) {
            $middle->push($rankItems[$pos-1]);
            $middle->push($rankItems[$pos]);
            $middle->push($rankItems[$pos+1]);
        }

        $sections->push($middle);
        $sections->push($bottomList);

        $sections->filter(function ($section) {
            return $section->count();
        });

        return $next($sections);
    }
}
