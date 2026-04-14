<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Projects/Index', [
            'projects' => Project::with('service:id,title')->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Projects/Create', [
            'services' => Service::active()->orderBy('sort_order')->get(['id', 'title']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_id' => 'nullable|exists:services,id',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'client_name' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:255',
            'completed_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $project = Project::create(collect($validated)->except(['thumbnail', 'images'])->toArray());

        if ($request->hasFile('thumbnail')) {
            $project->addMediaFromRequest('thumbnail')->toMediaCollection('project_thumbnail');
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $project->addMedia($image)->toMediaCollection('project_images');
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        return Inertia::render('Admin/Projects/Edit', [
            'project' => $project->load('media'),
            'services' => Service::active()->orderBy('sort_order')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_id' => 'nullable|exists:services,id',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'client_name' => 'nullable|string|max:255',
            'project_url' => 'nullable|url|max:255',
            'completed_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        $project->update(collect($validated)->except(['thumbnail', 'images', 'remove_images'])->toArray());

        if ($request->hasFile('thumbnail')) {
            $project->clearMediaCollection('project_thumbnail');
            $project->addMediaFromRequest('thumbnail')->toMediaCollection('project_thumbnail');
        }
        if (!empty($validated['remove_images'])) {
            $project->media()->whereIn('id', $validated['remove_images'])->each->delete();
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $project->addMedia($image)->toMediaCollection('project_images');
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->clearMediaCollection();
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }
}
