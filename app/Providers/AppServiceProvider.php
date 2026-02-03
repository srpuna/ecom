<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;

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
        // Share site settings with all views
        View::composer('*', function ($view) {
            $view->with('siteSettings', [
                'site_name' => SiteSetting::get('site_name', 'LuxeStore'),
                'navbar_logo' => SiteSetting::where('key', 'navbar_logo')->first(),
                'footer_logo' => SiteSetting::where('key', 'footer_logo')->first(),
                'favicon' => SiteSetting::where('key', 'favicon')->first(),
                'footer_qr_code' => SiteSetting::where('key', 'footer_qr_code')->first(),
            ]);
        });
    }
}
