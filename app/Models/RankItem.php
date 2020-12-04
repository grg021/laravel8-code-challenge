<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankItem extends Model
{

    private $data;
    private $user;

    public function __construct($data)
    {
        $this->data = $data;
        $this->user = auth()->user();
    }

    public function getTitleAttribute()
    {
        return User::findOrFail($this->data->user_id)->name;
    }

    public function getSubtitleAttribute()
    {
        return $this->data->points . ' PTS';
    }

    public function getHighlightAttribute()
    {

        if (!$this->user) {
            return 0;
        }

        return ($this->user->id == $this->data->user_id) ? 1 : 0;
    }

    public function getRankAttribute()
    {
        return $this->data->rank;
    }
}
