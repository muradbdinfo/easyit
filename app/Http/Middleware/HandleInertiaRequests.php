<?php
namespace App\Http\Middleware;

use App\Models\PageView;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'is_admin' => $request->user()->hasRole('admin'),
                ] : null,
            ],
            // Cache settings for 1 hour (cleared on admin update)
            'settings' => Cache::remember('app_settings', 3600, function () {
                return Setting::pluck('value', 'key');
            }),
            // Cache navigation for 1 hour
            'navigation' => [
                'services' => Cache::remember('nav_services', 3600, function () {
                    return Service::where('is_active', true)
                        ->orderBy('sort_order')
                        ->get(['id', 'title', 'slug']);
                }),
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],
            // Total page views (cached, synced on increment in middleware)
            'totalPageViews' => Cache::rememberForever('page_views_total', function () {
                return PageView::count();
            }),
        ];
    }
}
