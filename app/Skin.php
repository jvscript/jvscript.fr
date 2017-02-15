<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skin extends Model {

    /**
     * Get the user that owns the collection.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the poster user
     */
    public function poster_user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags() {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function isValidated() {
        return $this->status == 1;
    }

    public function statusLabel() {
        $label = ['En attente', 'Validé', 'Refusé'];
        return $label[$this->status];
    }

    protected $dates = [
        'last_update',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'autor', 'skin_url', 'repo_url', 'photo_url', 'topic_url', 'website_url', 'user_email', 'don_url', 'user_id'];

}
