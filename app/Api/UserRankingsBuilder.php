<?php

namespace App\Api;

use App\Models\Course;
use App\Query\UserRankingsQuery;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class UserRankingsBuilder implements SectionsBuilder
{
    private const MIN_SIZE = 3;
    private const MAX_SIZE = 9;

    private UserRankingsQuery $rankings;
    private ?Authenticatable $user;
    private Collection $rankItems;
    private Collection $sections;
    private int $size = self::MAX_SIZE;

    public function __construct(Course $course, $countryCode = null)
    {
        $this->rankings = new UserRankingsQuery();
        $this->rankings->course($course->id);
        if ($countryCode) {
            $this->rankings->country($countryCode);
        }
        $this->user = auth()->user();
        $this->sections = collect([]);
        $this->rankItems = collect([]);
        $this->rankItems = $this->rankings->get();
    }

    public function initialize()
    {
        $this->rankItems = $this->rankings->get();
    }

    public function build()
    {
        // build top section
        $topList = $this->topTier()->list();
        $this->sections->push($topList);
        $this->removeItemsFromList($topList);

        // build bottom first then check if middle section is needed
        $bottomList = $this->bottomTier()->list();
        $this->removeItemsFromList($bottomList);

        $middleList = $this->middleTier()->list();
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

    public function list(): Collection
    {
        return $this->prioritizeUserIfSameRank()->values();
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
        $this->size = ($this->rankings->count() <= self::MIN_SIZE || !$this->user)
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
        if (!$this->user) {
            return -1;
        }
        return $list->search(function ($item) {
            return $item->user_id == $this->user->id;
        });
    }

    protected function removeItemsFromList(Collection $list)
    {
        $this->rankItems = $this->rankItems->splice($list->count());
    }

    /**
     * @return Collection
     */
    protected function prioritizeUserIfSameRank(): Collection
    {
        $list = $this->rankItems->take($this->size)->sortBy('rank')->values();

        $pos = $this->getUserPosition($list);

        if ($pos > -1) {
            $rankItem = $list[$pos];

            $dups = $list->where('rank', $rankItem->rank);

            if ($dups->count() > 1 and $dups->first()->user_id != $rankItem->user_id) {
                $list->splice($pos, 1);
                $list->splice($dups->keys()->first(), 0, [$rankItem]);
            }
        }
        return $list;
    }
}
