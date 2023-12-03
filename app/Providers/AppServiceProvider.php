<?php

namespace App\Providers;
ini_set('memory_limit', '-1');
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use App\Models\BusinessSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        try {
            $timezone = BusinessSetting::where(['key' => 'time_zone'])->first();
            if (isset($timezone)) {
                config(['app.timezone' => $timezone->value]);
                date_default_timezone_set($timezone->value);
            }
        } catch (\Exception $exception) {
        }
    }
}
