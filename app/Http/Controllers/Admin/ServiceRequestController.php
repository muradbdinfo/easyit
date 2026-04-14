<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/ServiceRequests/Index', [
            'requests' => ServiceRequest::with('service:id,title')
                ->when($request->status, fn($q, $s) => $q->where('status', $s))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only('status'),
        ]);
    }

    public function show(ServiceRequest $serviceRequest)
    {
        return Inertia::render('Admin/ServiceRequests/Show', [
            'serviceRequest' => $serviceRequest->load('service:id,title'),
        ]);
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewing,quoted,accepted,rejected',
            'admin_notes' => 'nullable|string',
        ]);
        $serviceRequest->update($request->only('status', 'admin_notes'));
        return back()->with('success', 'Status updated.');
    }
}
