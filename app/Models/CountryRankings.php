<?php


namespace App\Models;


use App\Api\UserRankingsBuilder;

class CountryRankings extends Rankings
{

    public function __construct($user, Course $course)
    {
        parent::__construct($user, $course);
        $this->userRankings = new UserRankingsBuilder($this->course, $this->user->country_code);
    }

}
