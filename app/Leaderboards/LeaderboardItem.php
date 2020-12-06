<?php

namespace App\Leaderboards;

/**
 * Represents each item in the leaderboards
 */
class LeaderboardItem
{

    /**
     * @var int
     */
    public int $userId;

    /**
     * @var int
     */
    public int $points;

    /**
     * @var int
     */
    public int $rank = 0;

    /**
     * @var int
     */
    public int $highlight = 0;

    /**
     * @var int
     */
    public int $points_diff = 0;

    public function __construct($object)
    {
        $this->userId = $object->user_id;
        $this->points = $object->points;
    }
}
