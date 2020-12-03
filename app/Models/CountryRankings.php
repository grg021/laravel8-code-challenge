<?php


namespace App\Models;


class CountryRankings extends Rankings
{
    public function __construct($user, $course)
    {
        parent::__construct($user, $course);
        $this->sections = [];
        $section = collect([
            [
                'title' => 'Sandra Lidstream',
                'subtitle' => '205 PTS (+93)',
                'rank' => 1
            ],
            [
                'title' => 'Corvin Dalek',
                'subtitle' => '200 PTS (+88)',
                'rank' => 2
            ],
            [
                'title' => 'Kumar Jubar',
                'subtitle' => '180 PTS (+68)',
                'rank' => 3
            ],
            [
                'title' => 'Alfred Maroz',
                'subtitle' => '112 PTS',
                'rank' => 4,
                'highlight' => 1
            ],
            [
                'title' => 'Arthur Rembo',
                'subtitle' => '95 PTS',
                'rank' => 5
            ],
        ]);
        $this->sections[] = $section;

        $section = collect([
            [
                'title' => 'Gustaf Makinen',
                'subtitle' => '20 PTS',
                'rank' => 34
            ],
            [
                'title' => 'Selena Manesh',
                'subtitle' => '10 PTS',
                'rank' => 35
            ],
            [
                'title' => 'Adam Morrison',
                'subtitle' => '3 PTS',
                'rank' => 36
            ],
        ]);

        $this->sections[] = $section;
    }
}
