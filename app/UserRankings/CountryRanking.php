<?php

namespace App\UserRankings;

class CountryRanking extends WorldRanking
{

    protected $country;

    public function __construct($courseId, $country)
    {
        parent::__construct($courseId);
        $this->country = $country;
    }

    protected function filter()
    {
        return $this->query->where('users.country_code', '=', $this->country);
    }
}
