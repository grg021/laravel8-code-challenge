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
        foreach ($content->rankItems as $key => $item) {
            if ($key < $content->userKey) {
                $item->points_diff = $item->points - $content->userItem->points;
            }
        }
        return $content;
    }
}
