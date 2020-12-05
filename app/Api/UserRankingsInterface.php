<?php

namespace App\Api;

interface UserRankingsInterface
{
    public function get();
    public function course($courseId);
    public function country($countryCode);
    public function getUserRank($userId);
}
