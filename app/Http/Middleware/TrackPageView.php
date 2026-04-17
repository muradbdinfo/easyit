<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    /**
     * Route prefixes to exclude from tracking.
     */
    protected array $excludedPrefixes = [
        'admin',
        'client',
        'login',
        'register',
        'password',
        'email',
        'verify-email',
        'confirm-password',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET requests (HTML pages, not API/assets)
        if (
            $request->isMethod('GET') &&
            $response->getStatusCode() === 200 &&
            !$request->ajax() &&
            !$request->wantsJson() &&
            !$this->isExcludedPath($request) &&
            !$this->isAdmin($request)
        ) {
            $this->recordView($request);
        }

        return $response;
    }

    protected function isAdmin(Request $request): bool
    {
        $user = $request->user();

        return $user && $user->hasRole('admin');
    }

    protected function isExcludedPath(Request $request): bool
    {
        $path = trim($request->path(), '/');

        foreach ($this->excludedPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return true;
            }
        }

        // Exclude asset/file requests
        if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|ico|woff2?|ttf|map|xml|txt)$/i', $path)) {
            return true;
        }

        return false;
    }

    protected function recordView(Request $request): void
    {
        try {
            PageView::create([
                'url'        => '/' . trim($request->path(), '/'),
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer'   => $request->header('referer'),
                'viewed_at'  => now(),
            ]);

            // Increment cached counter (avoids full recount on every request)
            Cache::increment('page_views_total');
        } catch (\Throwable $e) {
            // Silently fail — tracking should never break the site
            report($e);
        }
    }
}
