<?php

namespace App\UserRankings;

use Illuminate\Database\Query\Builder;

class CountryRanking extends WorldRanking
{

    protected string $country;

    public function __construct($courseId, $country)
    {
        parent::__construct($courseId);
        $this->country = $country;
    }

    /**
     * @return Builder
     */
    protected function filter(): Builder
    {
        return $this->query->where('users.country_code', '=', $this->country);
    }
}
