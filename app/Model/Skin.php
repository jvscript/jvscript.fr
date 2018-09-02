<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Skin extends Model {

    /**
     * Get the user (owner)
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
        return $this->morphToMany('App\Model\Tag', 'taggable');
    }

    public function isValidated() {
        return $this->status == 1;
    }

    public function getPhotoUrlAttribute($value) {
        if ($value)
            return "/storage/images/" . $value;
        return null;
    }

    public function photoShortLink() {
        return str_replace("/storage/images/", '', $this->photo_url);
    }

    public function photoSmall() {
        if ($this->photo_url)
            return "/storage/images/small-" . $this->photoShortLink();
        return null;
    }

    public function statusLabel() {
        $label = ['En attente', 'Validé', 'Refusé'];
        return $label[$this->status];
    }

    /**
     * Get all of the item's comments.
     */
    public function comments() {
        return $this->morphMany('App\Model\Comment', 'commentable');
    }

    protected $dates = [
        'last_update',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'autor', 'skin_url', 'repo_url', 'version', 'last_update', 'photo_url', 'topic_url', 'website_url', 'user_email', 'don_url', 'user_id'];

}
