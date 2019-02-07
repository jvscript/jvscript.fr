<?php

namespace App\Model;

class Skin extends Script
{
    public function getUrlAttribute()
    {
        return $this->skin_url;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'autor', 'skin_url', 'repo_url', 'version', 'last_update', 'photo_url', 'topic_url', 'website_url', 'user_email', 'don_url', 'user_id'];
}
