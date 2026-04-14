<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Contacts/Index', [
            'contacts' => Contact::when($request->status, fn($q, $s) => $q->where('status', $s))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only('status'),
        ]);
    }

    public function show(Contact $contact)
    {
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }
        return Inertia::render('Admin/Contacts/Show', ['contact' => $contact]);
    }

    public function updateStatus(Request $request, Contact $contact)
    {
        $request->validate(['status' => 'required|in:new,read,replied,archived']);
        $contact->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted.');
    }
}
