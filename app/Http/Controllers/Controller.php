<?php

namespace App\Http\Controllers;

use App\Lib\Lib;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    protected $lib;
    protected $discord_url;
    protected $min_time_comment;
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->discord_url = config('services.discord.webhook_url');
        $this->lib = new Lib;
        $this->min_time_comment = 30; // Interval de temps entre chaque commentaire ou le captcha apparait
    }
}
