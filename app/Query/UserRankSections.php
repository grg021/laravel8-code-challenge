<?php

namespace App\Query;

use App\Models\Course;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class UserRankSections
{
    private const MIN_SIZE = 3;

    private UserRankingsQuery $rankings;
    private ?Authenticatable $user;
    private Collection $rankItems;
    private int $size;

    public function __construct(Course $course, $countryCode = null)
    {
        $this->rankings = new UserRankingsQuery();
        $this->rankings->course($course->id);
        if ($countryCode) {
            $this->rankings->country($countryCode);
        }
        $this->rankItems = $this->rankings->get();
        $this->user = auth()->user();
    }


    public function list(): Collection
    {

        $this->getTierSize();

        return $this->getRankedValues();
    }

    public function topTier(): UserRankSections
    {
        return $this;
    }

    public function bottomTier(): UserRankSections
    {
        $this->rankItems = $this->rankItems->sortByDesc('rank')->values();
        return $this;
    }

    protected function getRankedValues()
    {

        $list  = $this->rankItems->take($this->size)->sortBy('rank')->values();

        $pos = $this->getUserPosition($list);

        if ($pos > -1) {
            $rankItem = $list[$pos];

            $dups = $list->where('rank', $rankItem->rank);

            if ($dups->count() > 1 and $dups->first()->user_id != $rankItem->user_id) {
                $list->splice($pos, 1);
                $list->splice($dups->keys()->first(), 0, [$rankItem]);
            }
        }

        return $list->values();
    }

    private function getTierSize()
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
}
