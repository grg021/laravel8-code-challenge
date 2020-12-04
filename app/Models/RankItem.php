<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankItem extends Model
{

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getTitleAttribute()
    {
        $user = User::find($this->data->user_id);
        return ($user) ? $user->name : '';
    }

    public function getSubtitleAttribute()
    {
        return $this->data->points . ' PTS';
    }

    public function getHighlightAttribute()
    {
        return $this->data->highlight;
    }

    public function getRankAttribute()
    {
        return $this->data->rank;
    }
}
