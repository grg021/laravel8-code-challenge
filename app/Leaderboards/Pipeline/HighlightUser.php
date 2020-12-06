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
        if ($content->userKey > -1) {
            $content->rankItems[$content->userKey]->highlight = 1;
        }
        return $content;
    }
}
