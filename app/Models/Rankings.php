<?php

namespace App\Models;

use App\Api\UserRankingsBuilder;

class Rankings
{

    protected $user;
    protected $course;
    protected $userRankings;

    protected $sections = [
        RankingsTopSection::class,
        RankingsTopSection::class,
        RankingsBottomSection::class
    ];

    public function __construct($user, Course $course)
    {
        $this->user = $user;
        $this->course = $course;
        $this->userRankings = new UserRankingsBuilder($this->course);
    }

    public function get()
    {
        return collect($this->sections)
            ->map(fn($name) => (new $name($this->userRankings))->all())
            ->filter(function ($section) {
                return $section->count();
            });
    }

}
