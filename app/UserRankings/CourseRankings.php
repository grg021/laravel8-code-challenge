<?php

namespace App\UserRankings;

use Illuminate\Support\Collection;

interface CourseRankings
{
    /**
     * @return Collection
     * Get user rankings
     */
    public function get(): Collection;

}
