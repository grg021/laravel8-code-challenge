<?php

namespace App\Leaderboards\Pipeline;

use App\Leaderboards\Leaderboard;
use Closure;
use Illuminate\Support\Collection;

class BuildSection implements Pipe
{
    protected const MIN_SIZE = 3;
    protected const MAX_SIZE = 9;

    /**
     * @param $content
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($content, Closure $next)
    {
        return $next($this->build($content));
    }

    /**
     * @param  Leaderboard  $content
     * @return Leaderboard
     */
    protected function build(Leaderboard $content): Leaderboard
    {

        $index = $this->getIndex($content->rankItems, $content->userKey);
        $limit = $this->getLimit($content->rankItems, $content->userKey);

        $section = $content->rankItems->slice($index, $limit);
        $content->sections->push($section->values());

        return $content;
    }

    /**
     * @param  Collection  $rankItems
     * @param  int  $userKey
     * @return int
     */
    protected function getIndex(Collection $rankItems, int $userKey)
    {
        return 0;
    }

    /**
     * @param  Collection  $rankItems
     * @param  int  $userKey
     * @return int
     */
    protected function getLimit(Collection $rankItems, int $userKey)
    {
        return $rankItems->count();
    }
}
