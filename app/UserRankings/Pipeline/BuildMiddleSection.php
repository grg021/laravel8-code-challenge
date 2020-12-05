<?php

namespace App\UserRankings\Pipeline;

use Closure;

class BuildMiddleSection extends Section
{
    public function handle($content, Closure $next)
    {

        $list = $content[0];
        $userId = $content[1];
        $sections = $content[2];
        $bottomList = $content[3];

        $middle = collect([]);
        $pos = getUserPosition($list, $userId);
        if ($pos > -1) {
            $middle->push($list[$pos-1]);
            $middle->push($list[$pos]);
            $middle->push($list[$pos+1]);
        }

        $sections->push($middle);
        $sections->push($bottomList);

        $sections->filter(function ($section) {
            return $section->count();
        });

        return $next($sections);
    }
}
