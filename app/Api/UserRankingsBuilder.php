<?php

namespace App\Api;

use Illuminate\Support\Collection;

class UserRankingsBuilder implements SectionsBuilder
{
    private const MIN_SIZE = 3;
    private const MAX_SIZE = 9;

    private Collection $rankItems;
    private Collection $sections;
    private Collection $sectionItems;
    private int $size = self::MAX_SIZE;
    private int $userId;
    private $userRankItem;

    public function initialize(Collection $rankings, int $userId)
    {
        $this->sections = collect([]);
        $this->sectionItems = collect([]);
        $this->rankItems = $rankings;
        $this->userId = $userId;
        $this->userRankItem = $this->getUserRankItem();
        return $this;
    }

    public function build()
    {

        $this->prioritizeUserIfSameRank();
        $this->highlightUser();

        // build top section
        $topList = $this->setSectionSize()->getSectionItems();
        $this->removeItemsFromList($topList);

        // build bottom section
        $this->rankItems = $this->rankItems->reverse()->values();
        $bottomList = $this->setSectionSize()->getSectionItems();
        $this->removeItemsFromList($bottomList);

        // build middle section
        $this->rankItems = $this->rankItems->reverse()->values();
        $middleList = $this->middleTier()->getSectionItems();

        $this->sections->push($topList);
        $this->sections->push($middleList);
        $this->sections->push($bottomList);

        $this->addDiffInfo();

        return $this;
    }

    public function transform($transformer)
    {
        $this->sections = $this->sections->map(function (Collection $section) use ($transformer) {
            return $section->mapInto($transformer);
        });
        return $this;
    }

    public function get()
    {
        return $this->sections->filter(function ($section) {
            return $section->count();
        })->values();
    }

    private function getSectionItems(): Collection
    {
        $this->sectionItems = $this->rankItems->take($this->size)->sortBy('rank')->values();
        return $this->sectionItems;
    }

    public function middleTier(): UserRankingsBuilder
    {
        $middle = collect([]);
        $pos = getUserPosition($this->rankItems, $this->userId);
        if ($pos > -1) {
            $middle->push($this->rankItems[$pos-1]);
            $middle->push($this->rankItems[$pos]);
            $middle->push($this->rankItems[$pos+1]);
        }
        $this->rankItems = $middle;
        return $this;
    }

    private function setSectionSize($minimumSize = self::MIN_SIZE)
    {
        $this->size = ($this->rankItems->count() <= $minimumSize)
            ? $minimumSize
            : $this->getSizeBasedOnUserPosition($minimumSize);
        return $this;
    }

    private function getSizeBasedOnUserPosition($minimumSize): int
    {

        $pos = getUserPosition($this->rankItems, $this->userId);

        return ($pos > $minimumSize || $pos <= 0) ? $minimumSize : $pos + 2;
    }

    protected function removeItemsFromList(Collection $list)
    {
        $this->rankItems = $this->rankItems->splice($list->count());
    }

    protected function prioritizeUserIfSameRank()
    {
        $list = $this->rankItems;

        $pos = getUserPosition($list, $this->userId);

        if ($pos > -1) {
            $rankItem = $list[$pos];

            $dups = $list->where('rank', $rankItem->rank);

            if ($dups->count() > 1 and $dups->first()->user_id != $rankItem->user_id) {
                $list->splice($pos, 1);
                $list->splice($dups->keys()->first(), 0, [$rankItem]);
            }
        }
        $this->rankItems = $list;
        return $this;
    }

    private function highlightUser()
    {
        $list = $this->rankItems;

        $pos = getUserPosition($list, $this->userId);

        if ($pos > -1) {
            $rankItem = $list[$pos];
            $rankItem->highlight = 1;
            $list->splice($pos, 1, [$rankItem]);
        }
        $this->rankItems = $list;
        return $this;
    }

    private function getUserRankItem()
    {
        $pos = getUserPosition($this->rankItems, $this->userId);
        if ($pos > -1) {
            return $this->rankItems[$pos];
        }
    }

    private function addDiffInfo()
    {
        foreach ($this->sections as $section) {
            $pos = getUserPosition($section, $this->userId);
            if ($pos > -1) {
                foreach ($section as $key => $item) {
                    if ($item->user_id != $section[$pos]->user_id && $key < $pos) {
                        $item->points_diff = $item->points - $section[$pos]->points;
                    }
                }
            }
        }
    }

    public function getUserRank()
    {
        return ordinal($this->userRankItem->rank);
    }
}
