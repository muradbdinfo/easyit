<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => Testimonial::orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Testimonials/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'integer|min:1|max:5',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);
        $testimonial = Testimonial::create(collect($validated)->except('photo')->toArray());
        if ($request->hasFile('photo')) {
            $testimonial->addMediaFromRequest('photo')->toMediaCollection('photo');
        }
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial)
    {
        return Inertia::render('Admin/Testimonials/Edit', ['testimonial' => $testimonial->load('media')]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'integer|min:1|max:5',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);
        $testimonial->update(collect($validated)->except('photo')->toArray());
        if ($request->hasFile('photo')) {
            $testimonial->clearMediaCollection('photo');
            $testimonial->addMediaFromRequest('photo')->toMediaCollection('photo');
        }
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->clearMediaCollection();
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
