<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        $settings = Cache::rememberForever('app_settings', function () {
            return Setting::pluck('value', 'key');
        });

        return $settings->get($key, $default);
    }
}

if (!function_exists('settings_by_group')) {
    function settings_by_group(string $group)
    {
        return Setting::where('group', $group)->pluck('value', 'key');
    }
}

if (!function_exists('clear_settings_cache')) {
    function clear_settings_cache()
    {
        Cache::forget('app_settings');
    }
}
