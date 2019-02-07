<?php

namespace App\Http\Controllers;

use App;
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

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        if (App::environment('local', 'testing')) {
            $this->recaptcha_key = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
        } else { //prod
            $this->recaptcha_key = env('RECAPTCHA_KEY', '');
        }

        $this->discord_url = env('DISCORD_URL', '');
        $this->lib = new Lib();
        $this->min_time_comment = 30; //Interval de temps entre chaque commentaire ou le captcha apparait
        $this->min_time_captcha = 60; //Interval de temps entre chaque commentaire ou le captcha apparait
    }
}
