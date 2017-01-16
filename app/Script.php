<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Script extends Model {

    /**
     * Get the user that owns the collection.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags() {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    /**
     * Get the categorie of the collection.
     */
//    public function categorie() {
//        return $this->belongsTo(Categorie::class);
//    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'autor', 'js_url', 'repo_url', 'photo_url', 'user_email'];

}
