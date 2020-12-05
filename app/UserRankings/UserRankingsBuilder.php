<?php

namespace App\UserRankings;

use App\UserRankings\Pipeline\AddPointDifference;
use App\UserRankings\Pipeline\BuildBottomBuildSection;
use App\UserRankings\Pipeline\BuildMiddleBuildSection;
use App\UserRankings\Pipeline\BuildTopBuildSection;
use App\UserRankings\Pipeline\HighlightUser;
use App\UserRankings\Pipeline\PrioritizeUser;
use App\UserRankings\Popo\BuildSectionContent;
use App\UserRankings\Popo\PrepareRankingsContent;
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

    protected function prepareRankItems(): void
    {
        $content = new PrepareRankingsContent($this->rankItems, $this->userId);
        $this->rankItems = app(Pipeline::class)
            ->send($content)
            ->through([
                PrioritizeUser::class,
                HighlightUser::class,
                AddPointDifference::class
            ])
            ->then(function (PrepareRankingsContent $content) {
                return $content->rankItems;
            });
    }

    protected function buildSections(): void
    {
        $content = new BuildSectionContent($this->rankItems, $this->userId);
        $this->sections = app(Pipeline::class)
            ->send($content)
            ->through([
                BuildTopBuildSection::class,
                BuildBottomBuildSection::class,
                BuildMiddleBuildSection::class,
            ])
            ->then(function (BuildSectionContent $content) {
                return $content->sections;
            });
    }
}
