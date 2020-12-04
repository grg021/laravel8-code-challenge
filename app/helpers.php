<?php

use Illuminate\Support\Collection;

if (! function_exists('rank')) {
    function rank(Collection $data) : Collection
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
