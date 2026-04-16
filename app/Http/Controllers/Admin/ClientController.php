<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Clients/Index', [
            'clients' => User::role('client')
                ->withCount(['clientServices','tickets','invoices'])
                ->when($request->search, fn($q,$s) => $q->where('name','like',"%{$s}%")->orWhere('email','like',"%{$s}%"))
                ->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only('search'),
        ]);
    }

    public function create() { return Inertia::render('Admin/Clients/Create'); }

    public function store(Request $request)
    {
        $v = $request->validate(['name'=>'required|string|max:255','email'=>'required|email|unique:users','phone'=>'nullable|string|max:20','company'=>'nullable|string|max:255','password'=>'required|min:8']);
        $user = User::create([...$v, 'password'=>Hash::make($v['password']), 'email_verified_at'=>now()]);
        $user->assignRole('client');
        return redirect()->route('admin.clients.index')->with('success','Client created.');
    }

    public function show(User $client)
    {
        $client->load(['clientServices.service','tickets'=>fn($q)=>$q->latest()->take(10),'invoices'=>fn($q)=>$q->latest()->take(10)]);
        return Inertia::render('Admin/Clients/Show', ['client'=>$client]);
    }

    public function update(Request $request, User $client)
    {
        $v = $request->validate(['name'=>'required|string|max:255','phone'=>'nullable|string|max:20','company'=>'nullable|string|max:255','status'=>'required|in:active,suspended,inactive','notes'=>'nullable|string']);
        $client->update($v);
        return back()->with('success','Client updated.');
    }
}
