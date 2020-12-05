<?php

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
if (! function_exists('createRankItemObject')) {
    function createRankItemObject($userId, $points, $rank): object
    {
        return (object) [
            'user_id' => $userId,
            'points' => $points,
            'rank' => $rank,
            'highlight' => 0,
            'points_diff' => '0'
        ];
    }
}

if (! function_exists('getUserPosition')) {
    function getUserPosition(Collection $list, $userId)
    {
        $pos = $list->search(function ($item) use ($userId) {
            return $item->user_id == $userId;
        });
        return ($pos !== false) ? $pos : -1;
    }
}

