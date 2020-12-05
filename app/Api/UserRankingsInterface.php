<?php

namespace App\Api;

use Illuminate\Support\Collection;

interface UserRankingsInterface
{
    /**
     * @return Collection
     * Get user rankings
     */
    public function get();

    /**
     * @param $courseId
     * @return UserRankingsInterface
     * Sets the course filter
     */
    public function course($courseId);

    /**
     * @param $countryCode
     * @return UserRankingsInterface
     * Sets the country filter
     */
    public function country($countryCode);
}
