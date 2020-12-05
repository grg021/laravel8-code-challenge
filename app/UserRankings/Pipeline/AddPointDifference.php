<?php

namespace App\UserRankings\Pipeline;

use App\UserRankings\Popo\PrepareRankingsContent;
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
     * @param  PrepareRankingsContent  $content
     * @return PrepareRankingsContent
     */
    public function execute(PrepareRankingsContent $content)
    {
        $pos = getUserPosition($content->rankItems, $content->userId);
        if ($pos > -1) {
            foreach ($content->rankItems as $key => $item) {
                if ($item->user_id != $content->rankItems[$pos]->user_id && $key < $pos) {
                    $item->points_diff = $item->points - $content->rankItems[$pos]->points;
                }
            }
        }
        return $content;
    }
}
