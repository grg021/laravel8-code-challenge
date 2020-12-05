<?php


namespace App\UserRankings\Popo;


use Illuminate\Support\Collection;

class BuildSectionContent
{
    public Collection $rankItems;
    public Collection $sections;
    public Collection $sectionItems;
    public int $userId;

    public function __construct(Collection $rankItems, int $userId)
    {
        $this->rankItems = $rankItems;
        $this->userId = $userId;
        $this->sections = collect();
        $this->sectionItems = collect();
    }

}
