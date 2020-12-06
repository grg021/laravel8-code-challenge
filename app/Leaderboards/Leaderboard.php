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
     * @var LeaderboardItem|mixed|null
     */
    public ?LeaderboardItem $userItem;

    /**
     * @var int
     */
    public int $userKey;

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
        $this->prepareUserItem();
    }

    /**
     * @return string
     */
    public function getUserRank(): string
    {
        return getUserRank($this->sections, $this->userId);
    }

    /**
     * @return Collection
     */
    public function getSections(): Collection
    {
        return $this->sections->values();
    }

    public function getUserItem()
    {

        if ($this->userKey > -1) {
            return $this->rankItems[$this->userKey];
        }

        return null;
    }

    public function getUserKey()
    {
        return getUserPosition($this->rankItems, $this->userId);
    }

    /**
     * Takes note of info related to logged in user
     */
    protected function prepareUserItem(): void
    {
        $this->userKey = $this->getUserKey();
        $this->userItem = $this->getUserItem();
        if ($this->userKey > -1) {
            $this->rankItems[$this->userKey]->highlight = 1;
        }
    }
}
