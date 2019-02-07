<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Get all of the Script that are assigned this tag.
     */
    public function scripts()
    {
        return $this->morphedByMany('App\Model\Script', 'taggable');
    }

    /**
     * Get all of the skins that are assigned this tag.
     */
//    public function skins()
//    {
//        return $this->morphedByMany('App\Model\Skin', 'taggable');
//    }
}
