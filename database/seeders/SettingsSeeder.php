<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'company_name', 'value' => 'Easy IT', 'group' => 'general'],
            ['key' => 'company_tagline', 'value' => 'Your Trusted IT Partner', 'group' => 'general'],
            ['key' => 'company_description', 'value' => 'Easy IT provides comprehensive IT solutions including network infrastructure, web hosting, domain registration, web development, and ISP solutions in Sandwip, Chittagong, Bangladesh.', 'group' => 'general'],
            ['key' => 'company_logo', 'value' => null, 'group' => 'general'],
            ['key' => 'company_favicon', 'value' => null, 'group' => 'general'],

            // Contact
            ['key' => 'company_email', 'value' => 'info@easyit.com.bd', 'group' => 'contact'],
            ['key' => 'company_phone', 'value' => '+8801XXXXXXXXX', 'group' => 'contact'],
            ['key' => 'company_phone_2', 'value' => null, 'group' => 'contact'],
            ['key' => 'company_address', 'value' => 'Sandwip, Chittagong, Bangladesh', 'group' => 'contact'],
            ['key' => 'google_map_embed', 'value' => null, 'group' => 'contact'],

            // Social
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/easyit', 'group' => 'social'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/@easyit', 'group' => 'social'],
            ['key' => 'whatsapp_number', 'value' => '+8801XXXXXXXXX', 'group' => 'social'],
            ['key' => 'linkedin_url', 'value' => null, 'group' => 'social'],

            // SEO
            ['key' => 'meta_title', 'value' => 'Easy IT - Network Solutions, Web Hosting & Development', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'Easy IT provides professional network solutions, domain registration, web hosting, web development, server development, and ISP solutions in Bangladesh.', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => 'network solutions, web hosting, domain registration, web development, ISP solutions, Bangladesh', 'group' => 'seo'],
            ['key' => 'google_analytics_id', 'value' => null, 'group' => 'seo'],
            ['key' => 'facebook_pixel_id', 'value' => null, 'group' => 'seo'],

            // Stats (for homepage counters)
            ['key' => 'stats_clients', 'value' => '150', 'group' => 'stats'],
            ['key' => 'stats_projects', 'value' => '300', 'group' => 'stats'],
            ['key' => 'stats_experience_years', 'value' => '5', 'group' => 'stats'],
            ['key' => 'stats_support_hours', 'value' => '24/7', 'group' => 'stats'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
