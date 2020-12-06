<?php

namespace App\Leaderboards\Pipeline;

use Closure;

interface Pipe
{
    public function handle($content, Closure $next);
}
