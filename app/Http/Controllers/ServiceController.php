<?php
namespace App\Http\Controllers;

use App\Models\Service;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Services/Index', [
            'services' => Service::active()
                ->withCount('features', 'packages')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function show(Service $service)
    {
        $service->load([
            'features' => fn($q) => $q->orderBy('sort_order'),
            'packages' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')->with('features'),
            'faqs' => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
        ]);

        return Inertia::render('Public/Services/Show', [
            'service' => $service,
            'relatedProjects' => $service->projects()
                ->where('is_active', true)
                ->take(3)
                ->get(),
        ]);
    }
}
