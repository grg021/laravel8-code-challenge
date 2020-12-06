<?php

namespace App\Models;

use App\Leaderboards\LeaderboardItem;
use Illuminate\Database\Eloquent\Model;

class RankItem extends Model
{

    private LeaderboardItem $item;

    public function __construct(LeaderboardItem $item)
    {
        $this->item = $item;
    }

    public function getTitleAttribute()
    {
        $user = User::find($this->item->userId);
        return ($user) ? $user->name : '';
    }

    public function getSubtitleAttribute()
    {
        $subtitle = $this->item->points . ' PTS';
        if ($this->item->points_diff) {
            $subtitle .= ' ( +' . $this->item->points_diff . ')';
        }
        return $subtitle;
    }

    public function getHighlightAttribute()
    {
        return $this->item->highlight;
    }

    public function getRankAttribute()
    {
        return $this->item->rank;
    }
}
