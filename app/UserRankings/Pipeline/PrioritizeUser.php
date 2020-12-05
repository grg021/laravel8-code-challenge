<?php

namespace App\UserRankings\Pipeline;

use Closure;

class PrioritizeUser implements Pipe
{

    public function handle($content, Closure $next)
    {
        $list = $content[0];
        $userId = $content[1];

        $pos = getUserPosition($list, $userId);

        if ($pos > -1) {
            $rankItem = $list[$pos];

            $dups = $list->where('rank', $rankItem->rank);

            if ($dups->count() > 1 and $dups->first()->user_id != $rankItem->user_id) {
                $list->splice($pos, 1);
                $list->splice($dups->keys()->first(), 0, [$rankItem]);
            }
        }
        return $next([$list, $pos]);
    }
}
