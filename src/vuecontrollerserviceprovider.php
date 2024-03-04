<?php
namespace Yudhees\LaravelVueController;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Yudhees\LaravelVueController\vuecontroller;
class vuecontrollerserviceprovider extends ServiceProvider{
    public function boot()
    {
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        Route::post('/vuecontroller',vuecontroller::class)->name('vuecontroller');
    }   
}
