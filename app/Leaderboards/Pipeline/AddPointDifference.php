<?php

namespace App\Leaderboards\Pipeline;

use App\Leaderboards\Leaderboard;
use Closure;

class AddPointDifference implements Pipe
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
            foreach ($content->rankItems as $key => $item) {
                if ($item->userId != $content->rankItems[$pos]->userId && $key < $pos) {
                    $item->points_diff = $item->points - $content->rankItems[$pos]->points;
                }
            }
        }
        return $content;
    }
}
