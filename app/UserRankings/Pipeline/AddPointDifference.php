<?php


namespace App\UserRankings\Pipeline;


use Closure;

class AddPointDifference implements Pipe
{

    public function handle($content, Closure $next)
    {
        $list = $content[0];
        $userId = $content[1];
        $pos = getUserPosition($list, $userId);
        if ($pos > -1) {
            foreach ($list as $key => $item) {
                if ($item->user_id != $list[$pos]->user_id && $key < $pos) {
                    $item->points_diff = $item->points - $list[$pos]->points;
                }
            }
        }
        return $next($list);
    }
}
