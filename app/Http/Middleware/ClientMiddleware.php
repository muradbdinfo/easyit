<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) return redirect()->route('login');
        if ($request->user()->hasRole('admin')) return redirect()->route('admin.dashboard');
        if ($request->user()->status === 'suspended') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended. Please contact support.');
        }
        return $next($request);
    }
}
