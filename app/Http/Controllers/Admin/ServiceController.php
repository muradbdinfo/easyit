<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceFeature;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Services/Index', [
            'services' => Service::withCount('features', 'packages', 'projects')
                ->orderBy('sort_order')
                ->paginate(15),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Services/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'image' => 'nullable|image|max:2048',
            'features' => 'nullable|array',
            'features.*.title' => 'required|string|max:255',
            'features.*.description' => 'nullable|string',
        ]);

        $service = Service::create(collect($validated)->except(['features', 'image'])->toArray());

        if ($request->hasFile('image')) {
            $service->addMediaFromRequest('image')->toMediaCollection('service_image');
        }

        if (!empty($validated['features'])) {
            foreach ($validated['features'] as $index => $feature) {
                $service->features()->create([
                    'title' => $feature['title'],
                    'description' => $feature['description'] ?? null,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return Inertia::render('Admin/Services/Edit', [
            'service' => $service->load('features'),
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'image' => 'nullable|image|max:2048',
            'features' => 'nullable|array',
            'features.*.id' => 'nullable|integer',
            'features.*.title' => 'required|string|max:255',
            'features.*.description' => 'nullable|string',
        ]);

        $service->update(collect($validated)->except(['features', 'image'])->toArray());

        if ($request->hasFile('image')) {
            $service->clearMediaCollection('service_image');
            $service->addMediaFromRequest('image')->toMediaCollection('service_image');
        }

        if (isset($validated['features'])) {
            $existingIds = collect($validated['features'])->pluck('id')->filter();
            $service->features()->whereNotIn('id', $existingIds)->delete();

            foreach ($validated['features'] as $index => $feature) {
                $service->features()->updateOrCreate(
                    ['id' => $feature['id'] ?? null],
                    ['title' => $feature['title'], 'description' => $feature['description'] ?? null, 'sort_order' => $index]
                );
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->clearMediaCollection('service_image');
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
