<?php

namespace App\Leaderboards;

use Illuminate\Support\Collection;

interface CourseRankings
{
    /**
     * @return Collection
     * Get user rankings
     */
    public function get(): Collection;

}
