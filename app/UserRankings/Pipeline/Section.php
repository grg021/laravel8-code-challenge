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

    protected function getSizeBasedOnUserPosition($minimumSize, $list, $userId): int
    {

        $pos = getUserPosition($list, $userId);

        return ($pos > $minimumSize || $pos <= 0) ? $minimumSize : $pos + 2;
    }

    protected function removeItemsFromList(Collection $list, Collection $originalList)
    {
        return $originalList->splice($list->count());
    }

}
