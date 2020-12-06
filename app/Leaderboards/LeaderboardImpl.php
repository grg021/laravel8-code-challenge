<?php

namespace App\Leaderboards;

use App\Leaderboards\Pipeline\AddPointDifference;
use App\Leaderboards\Pipeline\BuildBottomBuildSection;
use App\Leaderboards\Pipeline\BuildMiddleBuildSection;
use App\Leaderboards\Pipeline\BuildTopBuildSection;
use App\Leaderboards\Pipeline\HighlightUser;
use App\Leaderboards\Pipeline\RankAndPrioritizeUser;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

class LeaderboardImpl implements LeaderboardBuilder
{

    /**
     * @var Leaderboard
     */
    private Leaderboard $leaderBoard;

    /**
     * @param  Collection  $rankings
     * @param  int  $userId
     * @return LeaderboardBuilder
     */
    public function initialize(Collection $rankings, int $userId): LeaderboardBuilder
    {
        $this->leaderBoard = new Leaderboard($rankings, $userId);
        return $this;
    }

    /**
     * @return LeaderboardBuilder
     */
    public function build(): LeaderboardBuilder
    {

        $this->prepareRankItems();

        $this->buildSections();

        return $this;
    }

    /**
     * @param $transformer
     * @return LeaderboardBuilder
     */
    public function transform($transformer): LeaderboardBuilder
    {
        $this->leaderBoard->sections = $this->leaderBoard->sections
            ->map(function (Collection $section) use ($transformer) {
                return $section->mapInto($transformer);
            });
        return $this;
    }

    /**
     * @return Leaderboard
     */
    public function get(): Leaderboard
    {
        return $this->leaderBoard;
    }

    /**
     * Determine and add necessary information to the rankings list
     */
    protected function prepareRankItems(): void
    {
        $this->leaderBoard = app(Pipeline::class)
            ->send($this->leaderBoard)
            ->through([
                HighlightUser::class,
                RankAndPrioritizeUser::class,
                AddPointDifference::class
            ])
            ->thenReturn();
    }

    /**
     * Build the top, middle and bottom section of the leaderboard
     */
    protected function buildSections(): void
    {
        $this->leaderBoard = app(Pipeline::class)
            ->send($this->leaderBoard)
            ->through([
                BuildTopBuildSection::class,
                BuildBottomBuildSection::class,
                BuildMiddleBuildSection::class,
            ])
            ->thenReturn();
    }
}
