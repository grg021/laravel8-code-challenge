<?php

namespace App\Leaderboards\Pipeline;

use App\Leaderboards\Leaderboard;
use Closure;

class RankAndPrioritizeUser implements Pipe
{

    /**
     * @param $content
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($content, Closure $next)
    {
        return $next($this->execute($content));
    }

    /**
     * @param  Leaderboard  $content
     * @return Leaderboard
     */
    protected function execute(Leaderboard $content): Leaderboard
    {
        $content->rankItems = rank($content->rankItems);
        $pos = getUserPosition($content->rankItems, $content->userId);

        if ($pos > -1) {
            $rankItem = $content->rankItems[$pos];

            $dups = $content->rankItems->where('rank', $rankItem->rank);

            if ($dups->count() > 1 and $dups->first()->userId != $rankItem->userId) {
                $content->rankItems->splice($pos, 1);
                $content->rankItems->splice($dups->keys()->first(), 0, [$rankItem]);
            }
        }
        return $content;
    }
}
