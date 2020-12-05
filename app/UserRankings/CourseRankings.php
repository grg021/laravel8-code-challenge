<?php

namespace App\UserRankings;

use Illuminate\Support\Collection;

interface CourseRankings
{
    // TODO return
    /**
     * @return Collection
     * Get user rankings
     */
    public function get(): Collection;

}
