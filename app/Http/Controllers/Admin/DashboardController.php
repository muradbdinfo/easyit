<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\PageView;
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
            'total_page_views' => PageView::count(),
            'today_page_views' => PageView::whereDate('viewed_at', today())->count(),
            'this_month_page_views' => PageView::whereMonth('viewed_at', now()->month)
                ->whereYear('viewed_at', now()->year)
                ->count(),
            // Comment stats
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
            'approved_comments' => Comment::where('status', 'approved')->count(),
            'spam_comments' => Comment::where('status', 'spam')->count(),
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

        // Page views per day (last 30 days)
        $pageViewsChart = PageView::select(
                DB::raw("DATE(viewed_at) as date"),
                DB::raw('COUNT(*) as count')
            )
            ->where('viewed_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top visited pages (last 30 days)
        $topPages = PageView::select('url', DB::raw('COUNT(*) as views'))
            ->where('viewed_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('url')
            ->orderByDesc('views')
            ->limit(10)
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

        // Recent comments
        $recentComments = Comment::with(['post:id,title,slug', 'user:id,name'])
            ->latest()
            ->take(5)
            ->get();

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
            'pageViewsChart' => $pageViewsChart,
            'topPages' => $topPages,
            'recentComments' => $recentComments,
        ]);
    }
}