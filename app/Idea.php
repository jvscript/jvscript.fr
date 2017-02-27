<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model {

    /**
     * Get the user that had writed the comment.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the item's comments.
     */
    public function comments() {
        return $this->morphMany('App\Comment', 'commentable');
    }

    public function isValidated() {
        return $this->status == 1;
    }

    public function statusLabel() {
        $label = ['En attente', 'Validé', 'Refusé'];
        return $label[$this->status];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'type', 'user_id' ,'status'
    ];

}
