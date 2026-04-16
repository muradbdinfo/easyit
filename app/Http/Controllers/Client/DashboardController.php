<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return Inertia::render('Client/Dashboard', [
            'stats' => [
                'activeServices' => $user->clientServices()->active()->count(),
                'openTickets' => $user->tickets()->open()->count(),
                'unpaidInvoices' => $user->invoices()->unpaid()->count(),
                'totalSpent' => $user->payments()->where('status','verified')->sum('amount'),
            ],
            'recentTickets' => $user->tickets()->with('category')->latest()->take(5)->get(),
            'unpaidInvoices' => $user->invoices()->unpaid()->orderBy('due_date')->take(5)->get(),
            'expiringServices' => $user->clientServices()->expiringSoon(30)->with('service')->get(),
        ]);
    }
}
