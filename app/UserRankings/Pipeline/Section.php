<?php


namespace App\UserRankings\Pipeline;


use Closure;
use Illuminate\Support\Collection;

class Section implements Pipe
{
    protected const MIN_SIZE = 3;
    protected const MAX_SIZE = 9;

    public function handle($content, Closure $next)
    {
        return $next($content);
    }

    protected function removeItemsFromList(Collection $list, Collection $originalList)
    {
        return $originalList->splice($list->count());
    }

    /**
     * @param $rankItems
     * @param  int  $size
     * @param $userId
     * @return int
     */
    protected function determineSizeForSection($rankItems, $userId, $maxSize): int
    {
        return ($rankItems->count() <= $maxSize)
            ? $maxSize
            : $this->getSizeBasedOnUserPosition($rankItems, $userId);
    }

    protected function getSizeBasedOnUserPosition($list, $userId): int
    {

        $pos = getUserPosition($list, $userId);

        return ($pos > self::MIN_SIZE || $pos <= 0) ? self::MIN_SIZE : $pos + 2;
    }

}
