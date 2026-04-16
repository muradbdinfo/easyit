<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = auth()->user()->tickets()
            ->with(['category','latestMessage'])
            ->when($request->status, fn($q,$s) => $q->where('status',$s))
            ->when($request->search, function($q, $s) { $q->where('subject','like',"%{$s}%")->orWhere('ticket_number','like',"%{$s}%"); })
            ->latest()->paginate(10)->withQueryString();

        return Inertia::render('Client/Tickets/Index', [
            'tickets' => $tickets,
            'filters' => $request->only(['status','search']),
            'statusCounts' => [
                'all'=>auth()->user()->tickets()->count(),
                'open'=>auth()->user()->tickets()->where('status','open')->count(),
                'in_progress'=>auth()->user()->tickets()->where('status','in_progress')->count(),
                'resolved'=>auth()->user()->tickets()->where('status','resolved')->count(),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Client/Tickets/Create', [
            'categories' => TicketCategory::active()->get(),
            'services' => auth()->user()->clientServices()->active()->with('service')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_category_id'=>'required|exists:ticket_categories,id',
            'client_service_id'=>'nullable|exists:client_services,id',
            'subject'=>'required|string|max:255',
            'priority'=>'required|in:low,medium,high,urgent',
            'message'=>'required|string|min:10|max:5000',
        ]);

        $ticket = auth()->user()->tickets()->create($validated);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('ticket-attachments','public');
            }
        }

        $ticket->messages()->create([
            'user_id'=>auth()->id(),'body'=>$request->message,'is_admin_reply'=>false,'attachments'=>$attachments ?: null,
        ]);

        return redirect()->route('client.tickets.show', $ticket)->with('success','Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $ticket->load(['category','clientService.service','messages'=>fn($q)=>$q->where('is_internal_note',false)->with('user')]);
        return Inertia::render('Client/Tickets/Show', ['ticket' => $ticket]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        if ($ticket->status === 'closed') return back()->with('error','This ticket is closed.');

        $request->validate(['body'=>'required|string|min:2|max:5000']);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) $attachments[] = $file->store('ticket-attachments','public');
        }

        $ticket->messages()->create([
            'user_id'=>auth()->id(),'body'=>$request->body,'is_admin_reply'=>false,'attachments'=>$attachments ?: null,
        ]);

        if ($ticket->status === 'waiting_on_client') $ticket->update(['status'=>'waiting_on_admin']);

        return back()->with('success','Reply sent.');
    }

    public function rate(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $request->validate(['rating'=>'required|integer|min:1|max:5','feedback'=>'nullable|string|max:500']);
        $ticket->update(['satisfaction_rating'=>$request->rating,'satisfaction_feedback'=>$request->feedback,'status'=>'closed','closed_at'=>now()]);
        return back()->with('success','Thank you for your feedback!');
    }
}
