<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Faqs/Index', [
            'faqs' => Faq::with('service:id,title')->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Faqs/Create', [
            'services' => Service::active()->orderBy('sort_order')->get(['id', 'title']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        Faq::create($validated);
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created.');
    }

    public function edit(Faq $faq)
    {
        return Inertia::render('Admin/Faqs/Edit', [
            'faq' => $faq,
            'services' => Service::active()->orderBy('sort_order')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        $faq->update($validated);
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }
}
