<?php

namespace App\UserRankings\Pipeline;

use App\UserRankings\Popo\PrepareRankingsContent;
use Closure;

class PrioritizeUser implements Pipe
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
    protected function execute(PrepareRankingsContent $content): PrepareRankingsContent
    {
        $pos = getUserPosition($content->rankItems, $content->userId);

        if ($pos > -1) {
            $rankItem = $content->rankItems[$pos];

            $dups = $content->rankItems->where('rank', $rankItem->rank);

            if ($dups->count() > 1 and $dups->first()->user_id != $rankItem->user_id) {
                $content->rankItems->splice($pos, 1);
                $content->rankItems->splice($dups->keys()->first(), 0, [$rankItem]);
            }
        }
        return $content;
    }
}
