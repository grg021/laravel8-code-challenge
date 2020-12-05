<?php

namespace App\UserRankings\Pipeline;

use App\UserRankings\Popo\PrepareRankingsContent;
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
     * @param  PrepareRankingsContent  $content
     * @return PrepareRankingsContent
     */
    public function execute(PrepareRankingsContent $content)
    {
        $pos = getUserPosition($content->rankItems, $content->userId);
        if ($pos > -1) {
            $rankItem = $content->rankItems[$pos];
            $rankItem->highlight = 1;
            $content->rankItems->splice($pos, 1, [$rankItem]);
        }
        return $content;
    }
}
