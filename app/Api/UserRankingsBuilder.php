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

    public function initialize(Collection $rankings, int $userId)
    {
        $this->sections = collect([]);
        $this->sectionItems = collect([]);
        $this->rankItems = $rankings;
        $this->userId = $userId;
        return $this;
    }

    public function build()
    {
        // build top section
        $topList = $this->topTier()->getSectionItems();
        $this->sections->push($topList);
        $this->removeItemsFromList($topList);

        // build bottom first then check if middle section is needed
        $bottomList = $this->bottomTier()->getSectionItems();
        $this->removeItemsFromList($bottomList);

        $middleList = $this->middleTier()->getSectionItems();
        $this->sections->push($middleList);
        $this->sections->push($bottomList);
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
        });
    }

    public function getSectionItems(): Collection
    {
        $this->getSectionList()
            ->highlightUser()
            ->prioritizeUserIfSameRank();
        return $this->sectionItems;
    }

    public function topTier(): UserRankingsBuilder
    {
        $this->setSectionSize();
        return $this;
    }

    public function middleTier(): UserRankingsBuilder
    {
        $middle = collect([]);
        $pos = $this->getUserPosition($this->rankItems);
        if ($pos > -1) {
            $middle->push($this->rankItems[$pos-1]);
            $middle->push($this->rankItems[$pos]);
            $middle->push($this->rankItems[$pos+1]);
        }
        $this->rankItems = $middle;
        return $this;
    }

    public function bottomTier(): UserRankingsBuilder
    {
        $this->rankItems = $this->rankItems->sortByDesc('rank')->values();
        $this->setSectionSize();
        return $this;
    }

    private function setSectionSize()
    {
        $this->size = ($this->rankItems->count() <= self::MIN_SIZE)
            ? self::MIN_SIZE
            : $this->getSizeBasedOnUserPosition(self::MIN_SIZE);
    }

    private function getSizeBasedOnUserPosition($minimumSize): int
    {

        $pos = $this->getUserPosition($this->rankItems);

        return (!$pos || $pos > $minimumSize) ? $minimumSize : $pos + 2;
    }


    protected function getUserPosition($list)
    {
        return $list->search(function ($item) {
            return $item->user_id == $this->userId;
        });
    }

    protected function removeItemsFromList(Collection $list)
    {
        $this->rankItems = $this->rankItems->splice($list->count());
    }

    /**
     * @return Collection
     */
    protected function prioritizeUserIfSameRank()
    {
        $list = $this->sectionItems;

        $pos = $this->getUserPosition($list);

        if ($pos > -1) {
            $rankItem = $list[$pos];

            $dups = $list->where('rank', $rankItem->rank);

            if ($dups->count() > 1 and $dups->first()->user_id != $rankItem->user_id) {
                $list->splice($pos, 1);
                $list->splice($dups->keys()->first(), 0, [$rankItem]);
            }
        }
        $this->sectionItems = $list;
        return $this;
    }

    private function highlightUser()
    {
        $list = $this->sectionItems;

        $pos = $this->getUserPosition($list);

        if ($pos > -1) {
            $rankItem = $list[$pos];
            $rankItem->highlight = 1;
            $list->splice($pos, 1, [$rankItem]);
        }
        $this->sectionItems = $list;
        return $this;
    }

    /**
     * @return Collection
     */
    protected function getSectionList()
    {
        $this->sectionItems = $this->rankItems->take($this->size)->sortBy('rank')->values();
        return $this;
    }
}
