<?php


namespace App\UserRankings\Pipeline;


use Closure;

class HighlightUser implements Pipe
{

    public function handle($content, Closure $next)
    {
        $list = $content[0];
        $userId = $content[1];

        $pos = getUserPosition($list, $userId);

        if ($pos > -1) {
            $rankItem = $list[$pos];
            $rankItem->highlight = 1;
            $list->splice($pos, 1, [$rankItem]);
        }
        return $next($list);
    }
}
