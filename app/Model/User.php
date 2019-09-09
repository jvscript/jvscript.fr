<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'github_id'
    ];
    protected $casts = [
        'admin' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->admin; // this looks for an admin column in your users table
    }

    /**
     * Get all of the factures line  for the facture
     */
    public function scripts()
    {
        return $this->hasMany(Script::class);
    }

    /**
     * Get all of the factures line  for the facture
     */
    public function skins()
    {
        return $this->hasMany(Skin::class);
    }

    /**
     * Get all of the comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
