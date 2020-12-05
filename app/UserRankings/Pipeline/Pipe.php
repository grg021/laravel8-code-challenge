<?php


namespace App\UserRankings\Pipeline;


use Closure;

interface Pipe
{
    public function handle($content, Closure $next);
}
