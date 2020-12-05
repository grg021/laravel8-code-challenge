<?php


namespace App\UserRankings;


use Illuminate\Support\Collection;

interface RankingsBuilderInterface
{

    /**
     * @param  Collection  $rankings
     * @param  int  $userId
     * @return RankingsBuilderInterface
     * Initialize the builder with the user rankings list and id of the logged in user
     */
    public function initialize(Collection $rankings, int $userId): RankingsBuilderInterface;

    /**
     * @return RankingsBuilderInterface
     * Build the rankings list
     */
    public function build(): RankingsBuilderInterface;

    /**
     * @param $transformer
     * @return RankingsBuilderInterface
     * Transform each item in the list to an object for front-end consumption
     */
    public function transform($transformer): RankingsBuilderInterface;

    /**
     * @return Collection
     * Get the collection of sections of rank items
     */
    public function get(): Collection;

    /**
     * @return string
     * Returns the ordinal rank of the user
     */
    public function getUserRank(): string;

}
