<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::where('is_active', true)
            ->with('service:id,title,slug')
            ->when($request->service, fn($q, $s) => $q->whereHas('service', fn($sq) => $sq->where('slug', $s)))
            ->orderBy('sort_order')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Public/Projects/Index', [
            'projects' => $projects,
            'services' => Service::active()->orderBy('sort_order')->get(['id', 'title', 'slug']),
            'filters' => $request->only('service'),
        ]);
    }

    public function show(Project $project)
    {
        $project->load('service:id,title,slug', 'media');

        return Inertia::render('Public/Projects/Show', [
            'project' => $project,
            'relatedProjects' => Project::where('is_active', true)
                ->where('id', '!=', $project->id)
                ->when($project->service_id, fn($q) => $q->where('service_id', $project->service_id))
                ->take(3)
                ->get(),
        ]);
    }
}
