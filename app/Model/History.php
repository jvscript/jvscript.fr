<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class History extends Model {

    
    protected $table = "historys";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ip', 'what', 'action'];

}
