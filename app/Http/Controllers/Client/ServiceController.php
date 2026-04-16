<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Client/Services/Index', [
            'services' => auth()->user()->clientServices()->with(['service','package'])
                ->orderByRaw("FIELD(status,'active','pending','suspended','expired','cancelled')")->get(),
        ]);
    }

    public function show($id)
    {
        $cs = auth()->user()->clientServices()->with(['service','package.features',
            'invoices'=>fn($q)=>$q->latest()->take(5),
            'tickets'=>fn($q)=>$q->latest()->take(5)
        ])->findOrFail($id);
        return Inertia::render('Client/Services/Show', ['clientService' => $cs]);
    }
}
