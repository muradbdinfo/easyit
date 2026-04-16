<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Client/Invoices/Index', [
            'invoices' => auth()->user()->invoices()->with('clientService.service')
                ->when($request->status, fn($q,$s)=>$q->where('status',$s))
                ->latest('issue_date')->paginate(10)->withQueryString(),
            'filters' => $request->only('status'),
            'summary' => [
                'totalDue'=>auth()->user()->invoices()->unpaid()->sum('total'),
                'totalPaid'=>auth()->user()->payments()->where('status','verified')->sum('amount'),
            ],
        ]);
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $invoice->load(['items','payments.user','clientService.service','user']);
        return Inertia::render('Client/Invoices/Show', ['invoice' => $invoice]);
    }
}
