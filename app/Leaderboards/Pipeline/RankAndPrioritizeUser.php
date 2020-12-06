<?php

namespace App\Leaderboards\Pipeline;

use App\Leaderboards\Leaderboard;
use Closure;
use Illuminate\Support\Collection;

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
            $itemsWithSameRank = $content->rankItems->where('rank', $rankItem->rank);
            $this->moveUserToTop($itemsWithSameRank, $rankItem, $content, $pos);
        }
        return $content;
    }

    /**
     * @param  Collection  $itemsWithSameRank
     * @param $rankItem
     * @param  Leaderboard  $content
     * @param  int  $pos
     */
    protected function moveUserToTop(
        Collection $itemsWithSameRank,
        $rankItem,
        Leaderboard $content,
        int $pos
    ): void {
        if ($itemsWithSameRank->count() > 1 and $itemsWithSameRank->first()->userId != $rankItem->userId) {
            $content->rankItems->splice($pos, 1);
            $content->rankItems->splice($itemsWithSameRank->keys()->first(), 0, [$rankItem]);
        }
    }
}
