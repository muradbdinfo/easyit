<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        // CSP - skip in local dev (Vite needs open access), apply in production only
        if (app()->environment('production')) {
            $response->headers->set('Content-Security-Policy',
                "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://connect.facebook.net https://pagead2.googlesyndication.com https://adservice.google.com https://www.googleadservices.com https://ep1.adtrafficquality.google https://fundingchoicesmessages.google.com; " .
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net; " .
                "img-src 'self' data: https: blob:; " .
                "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net; " .
                "connect-src 'self' https://www.google-analytics.com https://www.facebook.com https://pagead2.googlesyndication.com https://adservice.google.com; " .
                "frame-src 'self' https://www.google.com https://www.youtube.com https://googleads.g.doubleclick.net https://tpc.googlesyndication.com https://fundingchoicesmessages.google.com;"
            );
        }
        return $response;
    }
}