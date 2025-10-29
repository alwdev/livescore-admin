<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class SiteSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share site settings with specific views
        View::composer([
            'components.application-logo',
            'layouts.guest',
            'layouts.admin'
        ], function ($view) {
            $siteSetting = $this->getSiteSettings();
            $view->with('siteSetting', $siteSetting);
        });

        // Share with all views (optional)
        // View::share('siteSetting', $this->getSiteSettings());
    }

    /**
     * Get site settings with caching
     */
    private function getSiteSettings()
    {
        return Cache::remember('site_settings', 3600, function () {
            try {
                return SiteSetting::first() ?? $this->getDefaultSettings();
            } catch (\Exception $e) {
                // Return default settings if table doesn't exist yet
                return $this->getDefaultSettings();
            }
        });
    }

    /**
     * Get default site settings
     */
    private function getDefaultSettings()
    {
        return (object) [
            'site_name' => 'Livescore Admin',
            'logo' => null, // ✅ เปลี่ยนชื่อให้ตรงกับคอลัมน์ใน DB
            'favicon' => null,
            'seo_title' => 'Livescore Admin',
            'seo_description' => 'Complete football management dashboard',
            'seo_keywords' => 'livescore, football, admin',
            'contact_email' => 'admin@livescore.com',
            'contact_phone' => null,
            'contact_address' => null,
            'facebook' => null,
            'twitter' => null,
        ];
    }
}
