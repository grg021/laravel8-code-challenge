<?php

use App\Leaderboards\LeaderboardItem;
use Illuminate\Support\Collection;

if (! function_exists('rank')) {
    function rank(Collection $data): Collection
    {
        $prevScore = -1;
        $rank = 0;

        foreach ($data as $value) {
            $value->rank = ($prevScore == $value->points) ? $rank : $rank += 1;
            $prevScore = $value->points;
        }

        return $data;
    }
}
if (! function_exists('createLeaderboardItem')) {
    function createLeaderboardItem($userId, $points, $rank): object
    {
        $item = new LeaderboardItem((object) [
            'name' => 'Greg',
            'user_id' => $userId,
            'points' => $points,
        ]);
        $item->rank = $rank;
        return $item;
    }
}

if (! function_exists('getUserPosition')) {
    function getUserPosition(Collection $list, $userId)
    {
        $pos = $list->search(function ($item) use ($userId) {
            return $item->userId == $userId;
        });
        return ($pos !== false) ? $pos : -1;
    }
}

if (! function_exists('ordinal')) {
    function ordinal($number): string
    {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13)) {
            return $number. 'th';
        } else {
            return $number. $ends[$number % 10];
        }
    }
}

if (! function_exists('getUserRank')) {
    function getUserRank(Collection $sections, int $userId): string
    {
        foreach ($sections as $section) {
                $pos = getUserPosition($section, $userId);
            if ($pos > -1) {
                return ordinal($section[$pos]->rank);
            }
        }

        return '';
    }
}
