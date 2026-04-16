<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\ClientService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceAdminController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Invoices/Index', [
            'invoices' => Invoice::with(['user:id,name,email','clientService.service:id,title'])
                ->when($request->status, fn($q,$s)=>$q->where('status',$s))
                ->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only('status'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Invoices/Create', [
            'clients' => User::role('client')->get(['id','name','email']),
            'clientServices' => ClientService::with('service:id,title','user:id,name')->where('status','active')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'user_id'=>'required|exists:users,id','client_service_id'=>'nullable|exists:client_services,id',
            'issue_date'=>'required|date','due_date'=>'required|date','notes'=>'nullable|string',
            'items'=>'required|array|min:1','items.*.description'=>'required|string','items.*.quantity'=>'required|integer|min:1','items.*.unit_price'=>'required|numeric|min:0',
        ]);

        $subtotal = collect($v['items'])->sum(fn($i)=>$i['quantity']*$i['unit_price']);
        $invoice = Invoice::create([
            'user_id'=>$v['user_id'],'client_service_id'=>$v['client_service_id']??null,
            'subtotal'=>$subtotal,'total'=>$subtotal,'issue_date'=>$v['issue_date'],'due_date'=>$v['due_date'],'notes'=>$v['notes']??null,
        ]);

        foreach ($v['items'] as $item) {
            $invoice->items()->create([...$item, 'total'=>$item['quantity']*$item['unit_price']]);
        }

        return redirect()->route('admin.invoices.index')->with('success','Invoice created.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['user','items','payments.user','clientService.service']);
        return Inertia::render('Admin/Invoices/Show', ['invoice'=>$invoice]);
    }

    public function send(Invoice $invoice)
    {
        $invoice->update(['status'=>'sent']);
        return back()->with('success','Invoice sent to client.');
    }
}
