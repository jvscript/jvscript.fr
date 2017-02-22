<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {

    /**
     * Get all of the owning  models.
     */
    public function likeable() {
        return $this->morphTo();
    }

    /**
     * Get the user that had liked
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

}
