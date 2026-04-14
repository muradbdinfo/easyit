<?php
namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Setting;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Home', [
            'services' => Service::active()->orderBy('sort_order')->get(),
            'featuredProjects' => Project::where('is_active', true)
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->take(4)
                ->get(),
            'testimonials' => Testimonial::active()->take(6)->get(),
            'latestPosts' => Post::published()
                ->with('category:id,name,slug')
                ->latest('published_at')
                ->take(3)
                ->get(),
            'faqs' => Faq::active()->take(5)->get(),
            'stats' => [
                'clients' => Setting::get('stats_clients', '100'),
                'projects' => Setting::get('stats_projects', '200'),
                'experience' => Setting::get('stats_experience_years', '5'),
                'support' => Setting::get('stats_support_hours', '24/7'),
            ],
        ]);
    }
}
