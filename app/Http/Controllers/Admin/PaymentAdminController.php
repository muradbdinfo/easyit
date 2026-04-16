<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentAdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Payments/Index', [
            'payments' => Payment::with(['user:id,name','invoice:id,invoice_number,total'])->latest()->paginate(15),
        ]);
    }

    public function verify(Payment $payment)
    {
        $payment->update(['status'=>'verified','verified_at'=>now()]);
        $invoice = $payment->invoice;
        $totalPaid = $invoice->payments()->where('status','verified')->sum('amount');
        if ($totalPaid >= $invoice->total) $invoice->update(['status'=>'paid','paid_date'=>now()]);
        else $invoice->update(['status'=>'partially_paid']);
        return back()->with('success','Payment verified.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['admin_notes'=>'nullable|string']);
        $payment->update(['status'=>'rejected','admin_notes'=>$request->admin_notes]);
        return back()->with('success','Payment rejected.');
    }
}
