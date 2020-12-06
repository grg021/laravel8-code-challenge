<?php

namespace App\Leaderboards;

use App\Models\User;

/**
 * Represents each item in the leaderboards
 */
class LeaderboardItem
{

    /**
     * @var string
     */
    public string $name;

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

    /**
     * LeaderboardItem constructor.
     * @param $object
     */
    public function __construct($object)
    {
        $this->name = $object->name;
        $this->userId = $object->user_id;
        $this->points = $object->points;
    }

    /**
     * @return string
     */
    public function getSubtitle(): string
    {
        $subtitle = $this->points . ' PTS';
        if ($this->points_diff) {
            $subtitle .= ' ( +' . $this->points_diff . ')';
        }
        return $subtitle;
    }

}
