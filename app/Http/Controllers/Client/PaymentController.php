<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function create(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        return Inertia::render('Client/Payments/Create', [
            'invoice' => $invoice->load('items'),
            'paymentMethods' => [
                ['value'=>'bkash','label'=>'bKash','number'=>setting('bkash_number')],
                ['value'=>'nagad','label'=>'Nagad','number'=>setting('nagad_number')],
                ['value'=>'rocket','label'=>'Rocket','number'=>setting('rocket_number')],
                ['value'=>'bank_transfer','label'=>'Bank Transfer','details'=>setting('bank_details')],
            ],
        ]);
    }

    public function store(Request $request, Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $request->validate(['amount'=>'required|numeric|min:1','method'=>'required|in:bank_transfer,bkash,nagad,rocket,cash,other','transaction_id'=>'nullable|string|max:100','proof_image'=>'required|image|max:5120']);

        $proofPath = $request->file('proof_image')->store('payment-proofs','public');

        $invoice->payments()->create([
            'user_id'=>auth()->id(),'amount'=>$request->amount,'method'=>$request->method,
            'transaction_id'=>$request->transaction_id,'proof_image'=>$proofPath,'status'=>'pending',
        ]);

        return redirect()->route('client.invoices.show', $invoice)->with('success','Payment proof submitted. We will verify it shortly.');
    }
}
