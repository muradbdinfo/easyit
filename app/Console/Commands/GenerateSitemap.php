<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file including all public pages';

    public function handle(): int
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $now = now()->toW3cString();

        $urls = [];

        // Static pages
        $staticPages = [
            ['loc' => '/',                'priority' => '1.0',  'changefreq' => 'weekly'],
            ['loc' => '/about',           'priority' => '0.8',  'changefreq' => 'monthly'],
            ['loc' => '/services',        'priority' => '0.9',  'changefreq' => 'weekly'],
            ['loc' => '/projects',        'priority' => '0.8',  'changefreq' => 'weekly'],
            ['loc' => '/blog',            'priority' => '0.9',  'changefreq' => 'daily'],
            ['loc' => '/contact',         'priority' => '0.7',  'changefreq' => 'monthly'],
            ['loc' => '/faq',             'priority' => '0.6',  'changefreq' => 'monthly'],
            ['loc' => '/service-request', 'priority' => '0.7',  'changefreq' => 'monthly'],
            // AdSense required legal pages
            ['loc' => '/privacy-policy',  'priority' => '0.5',  'changefreq' => 'yearly'],
            ['loc' => '/terms',           'priority' => '0.5',  'changefreq' => 'yearly'],
            ['loc' => '/disclaimer',      'priority' => '0.5',  'changefreq' => 'yearly'],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc' => $baseUrl . $page['loc'],
                'lastmod' => $now,
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ];
        }

        // Services
        Service::where('is_active', true)->each(function ($service) use (&$urls, $baseUrl) {
            $urls[] = [
                'loc' => $baseUrl . '/services/' . $service->slug,
                'lastmod' => $service->updated_at->toW3cString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ];
        });

        // Projects
        Project::where('is_active', true)->each(function ($project) use (&$urls, $baseUrl) {
            $urls[] = [
                'loc' => $baseUrl . '/projects/' . $project->slug,
                'lastmod' => $project->updated_at->toW3cString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        });

        // Blog Posts
        Post::where('status', 'published')->each(function ($post) use (&$urls, $baseUrl) {
            $urls[] = [
                'loc' => $baseUrl . '/blog/' . $post->slug,
                'lastmod' => $post->updated_at->toW3cString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        });

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        File::put(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap generated with ' . count($urls) . ' URLs.');

        return Command::SUCCESS;
    }
}
