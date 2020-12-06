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
     * @return Leaderboard
     * Get the collection of sections of rank items
     */
    public function get(): Leaderboard;

}
