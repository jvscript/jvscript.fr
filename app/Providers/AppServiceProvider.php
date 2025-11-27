<?php

namespace App\Providers;

use App\Lib\Lib;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->lib = new Lib();
        Validator::extend('image_url', function ($attribute, $value) {
            return $this->lib->isImage($value);
        });
        Paginator::useBootstrapThree();
    }
}
