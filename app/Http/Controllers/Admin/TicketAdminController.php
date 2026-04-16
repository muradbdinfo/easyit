<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketAdminController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Tickets/Index', [
            'tickets' => Ticket::with(['user:id,name','category','latestMessage'])
                ->when($request->status, fn($q,$s)=>$q->where('status',$s))
                ->when($request->priority, fn($q,$p)=>$q->where('priority',$p))
                ->when($request->search, fn($q,$s)=>$q->where('subject','like',"%{$s}%")->orWhere('ticket_number','like',"%{$s}%"))
                ->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only(['status','priority','search']),
        ]);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user','category','clientService.service','messages.user','assignedAdmin:id,name']);
        return Inertia::render('Admin/Tickets/Show', [
            'ticket' => $ticket,
            'admins' => User::role('admin')->get(['id','name']),
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate(['body'=>'required|string|min:2']);
        $ticket->messages()->create(['user_id'=>auth()->id(),'body'=>$request->body,'is_admin_reply'=>true]);
        if (!$ticket->first_response_at) $ticket->update(['first_response_at'=>now()]);
        if ($ticket->status === 'open' || $ticket->status === 'waiting_on_admin') $ticket->update(['status'=>'waiting_on_client']);
        return back()->with('success','Reply sent.');
    }

    public function internalNote(Request $request, Ticket $ticket)
    {
        $request->validate(['body'=>'required|string']);
        $ticket->messages()->create(['user_id'=>auth()->id(),'body'=>$request->body,'is_admin_reply'=>true,'is_internal_note'=>true]);
        return back()->with('success','Note added.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status'=>'required|in:open,in_progress,waiting_on_client,waiting_on_admin,resolved,closed']);
        $data = ['status'=>$request->status];
        if ($request->status === 'resolved') $data['resolved_at'] = now();
        if ($request->status === 'closed') $data['closed_at'] = now();
        $ticket->update($data);
        return back()->with('success','Status updated.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate(['assigned_to'=>'nullable|exists:users,id']);
        $ticket->update(['assigned_to'=>$request->assigned_to]);
        return back()->with('success','Ticket assigned.');
    }
}
