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

        if ($content->userItem) {
            $itemsWithSameRank = $content->rankItems->where('rank', $content->userItem->rank);
            $this->moveUserToTop($itemsWithSameRank, $content->userItem, $content);
        }
        return $content;
    }

    /**
     * @param  Collection  $itemsWithSameRank
     * @param $rankItem
     * @param  Leaderboard  $content
     */
    protected function moveUserToTop(
        Collection $itemsWithSameRank,
        $rankItem,
        Leaderboard $content
    ): void {
        if ($itemsWithSameRank->count() > 1 and $itemsWithSameRank->first()->userId != $rankItem->userId) {
            $content->rankItems->splice($content->userKey, 1);
            $content->rankItems->splice($itemsWithSameRank->keys()->first(), 0, [$rankItem]);
        }
    }
}
