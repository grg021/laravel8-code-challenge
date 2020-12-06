<?php


namespace App\Leaderboards;

use Illuminate\Support\Collection;

interface LeaderboardBuilder
{

    /**
     * @param  Collection  $rankings
     * @param  int  $userId
     * @return LeaderboardBuilder
     * Initialize the builder with the user rankings list and id of the logged in user
     */
    public function initialize(Collection $rankings, int $userId): LeaderboardBuilder;

    /**
     * @return LeaderboardBuilder
     * Build the rankings list
     */
    public function build(): LeaderboardBuilder;

    /**
     * @param $transformer
     * @return LeaderboardBuilder
     * Transform each item in the list to an object for front-end consumption
     */
    public function transform($transformer): LeaderboardBuilder;

    /**
     * @return Collection
     * Get the collection of sections of rank items
     */
    public function get(): Collection;

}
