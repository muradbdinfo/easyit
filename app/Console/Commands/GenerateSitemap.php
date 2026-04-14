<?php
namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml for the website';

    public function handle()
    {
        $baseUrl = config('app.url');

        $urls = collect();

        // Static pages
        $urls->push(['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily']);
        $urls->push(['url' => '/about', 'priority' => '0.8', 'changefreq' => 'monthly']);
        $urls->push(['url' => '/services', 'priority' => '0.9', 'changefreq' => 'weekly']);
        $urls->push(['url' => '/projects', 'priority' => '0.8', 'changefreq' => 'weekly']);
        $urls->push(['url' => '/blog', 'priority' => '0.8', 'changefreq' => 'daily']);
        $urls->push(['url' => '/contact', 'priority' => '0.7', 'changefreq' => 'monthly']);
        $urls->push(['url' => '/service-request', 'priority' => '0.7', 'changefreq' => 'monthly']);
        $urls->push(['url' => '/faq', 'priority' => '0.6', 'changefreq' => 'monthly']);

        // Services
        Service::where('is_active', true)->each(function ($service) use ($urls) {
            $urls->push([
                'url' => "/services/{$service->slug}",
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $service->updated_at->toW3cString(),
            ]);
        });

        // Projects
        Project::where('is_active', true)->each(function ($project) use ($urls) {
            $urls->push([
                'url' => "/projects/{$project->slug}",
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => $project->updated_at->toW3cString(),
            ]);
        });

        // Blog posts
        Post::where('status', 'published')->each(function ($post) use ($urls) {
            $urls->push([
                'url' => "/blog/{$post->slug}",
                'priority' => '0.7',
                'changefreq' => 'weekly',
                'lastmod' => $post->updated_at->toW3cString(),
            ]);
        });

        // Blog categories
        Category::each(function ($category) use ($urls) {
            $urls->push([
                'url' => "/blog/category/{$category->slug}",
                'priority' => '0.5',
                'changefreq' => 'weekly',
            ]);
        });

        // Generate XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $entry) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= "    <loc>{$baseUrl}{$entry['url']}</loc>" . PHP_EOL;
            if (isset($entry['lastmod'])) {
                $xml .= "    <lastmod>{$entry['lastmod']}</lastmod>" . PHP_EOL;
            }
            $xml .= "    <changefreq>{$entry['changefreq']}</changefreq>" . PHP_EOL;
            $xml .= "    <priority>{$entry['priority']}</priority>" . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        File::put(public_path('sitemap.xml'), $xml);

        $this->info("Sitemap generated with {$urls->count()} URLs at public/sitemap.xml");

        return Command::SUCCESS;
    }
}
