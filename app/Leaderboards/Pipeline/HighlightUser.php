<?php

namespace App\Leaderboards\Pipeline;

use App\Leaderboards\Leaderboard;
use Closure;

class HighlightUser implements Pipe
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
        $pos = getUserPosition($content->rankItems, $content->userId);
        if ($pos > -1) {
            $rankItem = $content->rankItems[$pos];
            $rankItem->highlight = 1;
        }
        return $content;
    }
}
