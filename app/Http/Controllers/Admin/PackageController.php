<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Packages/Index', [
            'packages' => Package::with('service:id,title')->withCount('features')->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Packages/Create', [
            'services' => Service::active()->orderBy('sort_order')->get(['id', 'title']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly,one-time',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'features' => 'nullable|array',
            'features.*.feature' => 'required|string|max:255',
            'features.*.is_included' => 'boolean',
        ]);

        $package = Package::create(collect($validated)->except('features')->toArray());

        if (!empty($validated['features'])) {
            foreach ($validated['features'] as $i => $f) {
                $package->features()->create([
                    'feature' => $f['feature'],
                    'is_included' => $f['is_included'] ?? true,
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package created.');
    }

    public function edit(Package $package)
    {
        return Inertia::render('Admin/Packages/Edit', [
            'package' => $package->load('features'),
            'services' => Service::active()->orderBy('sort_order')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly,one-time',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'features' => 'nullable|array',
            'features.*.id' => 'nullable|integer',
            'features.*.feature' => 'required|string|max:255',
            'features.*.is_included' => 'boolean',
        ]);

        $package->update(collect($validated)->except('features')->toArray());

        if (isset($validated['features'])) {
            $existingIds = collect($validated['features'])->pluck('id')->filter();
            $package->features()->whereNotIn('id', $existingIds)->delete();

            foreach ($validated['features'] as $i => $f) {
                $package->features()->updateOrCreate(
                    ['id' => $f['id'] ?? null],
                    ['feature' => $f['feature'], 'is_included' => $f['is_included'] ?? true, 'sort_order' => $i]
                );
            }
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package updated.');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted.');
    }
}
