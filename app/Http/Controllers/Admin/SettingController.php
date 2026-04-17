<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings' => Setting::all()->groupBy('group'),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable|string',
        ]);

        foreach ($validated['settings'] as $setting) {
            Setting::where('key', $setting['key'])->update(['value' => $setting['value']]);
        }

        // Clear all relevant caches
        Cache::forget('app_settings');
        Cache::forget('nav_services');

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Upload company logo or favicon.
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:png,jpg,jpeg,gif,svg,webp,ico|max:2048',
            'key' => 'required|string|in:company_logo,company_favicon',
        ]);

        $key = $request->input('key');

        // Delete old file if exists
        $oldValue = Setting::where('key', $key)->value('value');
        if ($oldValue && Storage::disk('public')->exists($oldValue)) {
            Storage::disk('public')->delete($oldValue);
        }

        // Store new file
        $folder = $key === 'company_favicon' ? 'settings/favicon' : 'settings/logo';
        $path = $request->file('file')->store($folder, 'public');

        // Update setting
        Setting::where('key', $key)->update(['value' => $path]);

        // Clear cache
        Cache::forget('app_settings');

        return back()->with('success', ucfirst(str_replace('_', ' ', $key)) . ' updated successfully.');
    }

    /**
     * Remove company logo or favicon.
     */
    public function removeFile(Request $request)
    {
        $request->validate([
            'key' => 'required|string|in:company_logo,company_favicon',
        ]);

        $key = $request->input('key');

        $oldValue = Setting::where('key', $key)->value('value');
        if ($oldValue && Storage::disk('public')->exists($oldValue)) {
            Storage::disk('public')->delete($oldValue);
        }

        Setting::where('key', $key)->update(['value' => null]);

        Cache::forget('app_settings');

        return back()->with('success', ucfirst(str_replace('_', ' ', $key)) . ' removed.');
    }
}