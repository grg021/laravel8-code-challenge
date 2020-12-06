<?php


namespace App\Leaderboards;


use Illuminate\Support\Collection;

class Leaderboard
{
    /**
     * @var Collection
     */
    public Collection $rankItems;

    /**
     * @var Collection
     */
    public Collection $sections;

    /**
     * @var Collection
     */
    public Collection $sectionItems;

    /**
     * @var int
     */
    public int $userId;

    /**
     * Leaderboard constructor.
     * @param  Collection  $rankItems
     * @param  int  $userId
     */
    public function __construct(Collection $rankItems, int $userId)
    {
        $this->rankItems = $rankItems;
        $this->userId = $userId;
        $this->sections = collect();
        $this->sectionItems = collect();
    }
}
