<?php

namespace CrudApi;

use Illuminate\Support\ServiceProvider;

class CrudApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
//        $this->app->singleton('crud', function () {
//            return new Admin;
//        });
        $this->loadHelpers();

    }

    /**
     * Load the Backpack helper methods, for convenience.
     */
    public function loadHelpers()
    {
        require_once __DIR__.'/helpers.php';
    }
}
