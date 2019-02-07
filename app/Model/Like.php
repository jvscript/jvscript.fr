<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

    /**
     * Get all of the owning  models.
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that had liked
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'liked', 'user_id'
    ];
}
