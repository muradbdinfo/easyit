<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ClientService;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ClientServices/Index', [
            'clientServices' => ClientService::with(['user:id,name,email','service:id,title','package:id,name'])->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/ClientServices/Create', [
            'clients' => User::role('client')->get(['id','name','email']),
            'services' => Service::active()->with('packages')->orderBy('sort_order')->get(['id','title']),
        ]);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'user_id'=>'required|exists:users,id','service_id'=>'required|exists:services,id','package_id'=>'nullable|exists:packages,id',
            'label'=>'required|string|max:255','description'=>'nullable|string','status'=>'required|in:active,pending,suspended',
            'billing_cycle'=>'required|in:monthly,quarterly,yearly,one-time','price'=>'required|numeric|min:0',
            'start_date'=>'required|date','next_renewal_date'=>'nullable|date','specs'=>'nullable|array',
        ]);
        ClientService::create($v);
        return redirect()->route('admin.client-services.index')->with('success','Service assigned.');
    }

    public function edit(ClientService $clientService)
    {
        return Inertia::render('Admin/ClientServices/Edit', [
            'clientService' => $clientService->load(['user:id,name','service:id,title','package:id,name']),
            'services' => Service::active()->with('packages')->get(['id','title']),
        ]);
    }

    public function update(Request $request, ClientService $clientService)
    {
        $v = $request->validate([
            'label'=>'required|string|max:255','status'=>'required|in:active,suspended,expired,cancelled,pending',
            'price'=>'required|numeric|min:0','next_renewal_date'=>'nullable|date','admin_notes'=>'nullable|string',
            'specs'=>'nullable|array','credentials'=>'nullable|array',
        ]);
        $clientService->update($v);
        return back()->with('success','Service updated.');
    }
}
