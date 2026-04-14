<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Testimonial;
use App\Models\TeamMember;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $stats = [
            'total_services' => Service::count(),
            'total_projects' => Project::count(),
            'total_posts' => Post::count(),
            'total_testimonials' => Testimonial::count(),
            'total_team' => TeamMember::where('is_active', true)->count(),
            'new_contacts' => Contact::where('status', 'new')->count(),
            'total_contacts' => Contact::count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'total_requests' => ServiceRequest::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
        ];

        // Contacts per month (last 6 months)
        $contactsChart = Contact::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Service requests per month (last 6 months)
        $requestsChart = ServiceRequest::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Service requests by status
        $requestsByStatus = ServiceRequest::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Contacts by status
        $contactsByStatus = Contact::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Recent contacts
        $recentContacts = Contact::latest()->take(5)->get();

        // Recent service requests
        $recentRequests = ServiceRequest::with('service:id,title')
            ->latest()
            ->take(5)
            ->get();

        // Popular services (by request count)
        $popularServices = Service::withCount('serviceRequests')
            ->orderByDesc('service_requests_count')
            ->take(5)
            ->get(['id', 'title', 'service_requests_count']);

        // Recent blog posts
        $recentPosts = Post::with('user:id,name')
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'status', 'views', 'created_at', 'user_id']);

        // Total blog views
        $totalViews = Post::sum('views');

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'contactsChart' => $contactsChart,
            'requestsChart' => $requestsChart,
            'requestsByStatus' => $requestsByStatus,
            'contactsByStatus' => $contactsByStatus,
            'recentContacts' => $recentContacts,
            'recentRequests' => $recentRequests,
            'popularServices' => $popularServices,
            'recentPosts' => $recentPosts,
            'totalViews' => $totalViews,
        ]);
    }
}
