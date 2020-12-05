<?php

namespace App\UserRankings;

use Illuminate\Support\Collection;

interface RankingsQueryInterface
{
    /**
     * @return Collection
     * Get user rankings
     */
    public function get(): Collection;

    /**
     * @param $courseId
     * @return RankingsQueryInterface
     * Sets the course filter
     */
    public function course($courseId): RankingsQueryInterface;

    /**
     * @param $countryCode
     * @return RankingsQueryInterface
     * Sets the country filter
     */
    public function country($countryCode): RankingsQueryInterface;
}
