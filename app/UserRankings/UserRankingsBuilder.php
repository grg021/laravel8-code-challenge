<?php

namespace App\UserRankings;

use App\UserRankings\Pipeline\BuildBottomSection;
use App\UserRankings\Pipeline\BuildMiddleSection;
use App\UserRankings\Pipeline\BuildTopSection;
use App\UserRankings\Pipeline\HighlightUser;
use App\UserRankings\Pipeline\PrioritizeUser;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

class UserRankingsBuilder implements RankingsBuilderInterface
{
    private Collection $rankItems;
    private Collection $sections;
    private int $userId;
    private $userRankItem;

    public function initialize(Collection $rankings, int $userId): RankingsBuilderInterface
    {
        $this->sections = collect([]);
        $this->rankItems = $rankings;
        $this->userId = $userId;
        $this->userRankItem = $this->getUserRankItem();
        return $this;
    }

    public function build(): RankingsBuilderInterface
    {

        $this->prepareRankItems();

        $this->buildSections();

        $this->addDiffInfo();

        return $this;
    }

    public function transform($transformer): RankingsBuilderInterface
    {
        $this->sections = $this->sections->map(function (Collection $section) use ($transformer) {
            return $section->mapInto($transformer);
        });
        return $this;
    }

    public function get(): Collection
    {
        return $this->sections->filter(function ($section) {
            return $section->count();
        })->values();
    }

    public function getUserRank(): string
    {
        return ($this->userRankItem) ? ordinal($this->userRankItem->rank) : '';
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

    protected function prepareRankItems(): void
    {
        $this->rankItems = app(Pipeline::class)
            ->send([$this->rankItems, $this->userId])
            ->through([
                PrioritizeUser::class,
                HighlightUser::class,
            ])
            ->then(function ($content) {
                return $content;
            });
    }

    protected function buildSections(): void
    {

        $this->sections = app(Pipeline::class)
            ->send([$this->rankItems, $this->userId, $this->sections])
            ->through([
                BuildTopSection::class,
                BuildBottomSection::class,
                BuildMiddleSection::class,
            ])
            ->then(function ($content) {
                return $content;
            });
    }
}
