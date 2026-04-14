<?php
namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceFeature;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Network Solutions',
                'short_description' => 'Complete network infrastructure design, installation, and maintenance for businesses of all sizes.',
                'description' => '<h3>Professional Network Infrastructure</h3><p>We design and implement robust network solutions tailored to your business needs. From small office setups to enterprise-grade infrastructure, our team ensures reliable connectivity and optimal performance.</p><h3>What We Offer</h3><p>Our network solutions cover everything from initial planning and design to installation, configuration, and ongoing maintenance. We work with the latest networking equipment and follow industry best practices.</p>',
                'icon' => 'network',
                'is_featured' => true,
                'sort_order' => 1,
                'features' => [
                    'LAN/WAN Design & Installation',
                    'Network Security & Firewall Setup',
                    'Wireless Network Solutions',
                    'Network Monitoring & Maintenance',
                    'VPN Configuration',
                    'Network Troubleshooting',
                ],
            ],
            [
                'title' => 'Domain Registration',
                'short_description' => 'Register your perfect domain name with competitive pricing and free DNS management.',
                'description' => '<h3>Secure Your Online Identity</h3><p>Get your business online with the perfect domain name. We offer competitive pricing on all popular TLDs including .com, .net, .org, .com.bd, and many more.</p><h3>Included Services</h3><p>Every domain registration comes with free DNS management, domain forwarding, and privacy protection options.</p>',
                'icon' => 'globe',
                'is_featured' => true,
                'sort_order' => 2,
                'features' => [
                    '.com, .net, .org, .com.bd Registration',
                    'Free DNS Management',
                    'Domain Privacy Protection',
                    'Easy Domain Transfer',
                    'Auto-Renewal Option',
                    'Bulk Domain Registration',
                ],
            ],
            [
                'title' => 'Web Hosting',
                'short_description' => 'Fast, secure, and reliable web hosting solutions with 99.9% uptime guarantee.',
                'description' => '<h3>Reliable Hosting Solutions</h3><p>Host your website on our high-performance servers with guaranteed 99.9% uptime. We offer shared hosting, VPS, and dedicated server options to match your needs and budget.</p><h3>Why Choose Our Hosting</h3><p>All plans come with free SSL, automated backups, one-click app installers, and 24/7 technical support.</p>',
                'icon' => 'server',
                'is_featured' => true,
                'sort_order' => 3,
                'features' => [
                    'Shared, VPS & Dedicated Hosting',
                    'Free SSL Certificate',
                    'cPanel Control Panel',
                    'Daily Automated Backups',
                    '99.9% Uptime Guarantee',
                    '24/7 Technical Support',
                ],
            ],
            [
                'title' => 'Web Development',
                'short_description' => 'Custom website and web application development using modern technologies.',
                'description' => '<h3>Modern Web Development</h3><p>We build custom websites and web applications using cutting-edge technologies including Laravel, Vue.js, and React. From business websites to complex e-commerce platforms, we deliver solutions that drive results.</p><h3>Our Approach</h3><p>We follow agile development methodology, ensuring transparent communication and iterative delivery throughout the project lifecycle.</p>',
                'icon' => 'code',
                'is_featured' => true,
                'sort_order' => 4,
                'features' => [
                    'Custom Website Development',
                    'E-commerce Solutions',
                    'Web Application Development',
                    'Responsive & Mobile-First Design',
                    'SEO Optimization',
                    'Ongoing Maintenance & Support',
                ],
            ],
            [
                'title' => 'Server Development',
                'short_description' => 'Custom server setup, configuration, and management for optimal performance.',
                'description' => '<h3>Server Infrastructure Solutions</h3><p>We provide end-to-end server solutions including setup, configuration, optimization, and ongoing management. Whether you need a web server, mail server, or database server, we have you covered.</p>',
                'icon' => 'database',
                'is_featured' => false,
                'sort_order' => 5,
                'features' => [
                    'Linux & Windows Server Setup',
                    'Server Hardening & Security',
                    'Performance Optimization',
                    'Mail Server Configuration',
                    'Database Server Management',
                    'Server Migration Services',
                ],
            ],
            [
                'title' => 'Network Consultancy',
                'short_description' => 'Expert network consulting services to plan, optimize, and secure your IT infrastructure.',
                'description' => '<h3>Expert IT Consulting</h3><p>Our experienced network consultants help you plan, design, and optimize your IT infrastructure. We provide honest, vendor-neutral advice to ensure you get the best solutions for your budget.</p>',
                'icon' => 'users',
                'is_featured' => false,
                'sort_order' => 6,
                'features' => [
                    'Network Assessment & Audit',
                    'Infrastructure Planning',
                    'Security Consulting',
                    'Technology Roadmap',
                    'Vendor Selection Support',
                    'IT Budget Planning',
                ],
            ],
            [
                'title' => 'IP Log Server (ISP)',
                'short_description' => 'BTRC-compliant IP log server solutions for Internet Service Providers.',
                'description' => '<h3>ISP Compliance Solutions</h3><p>We provide BTRC-compliant IP log server solutions designed specifically for Internet Service Providers in Bangladesh. Our solutions ensure you meet all regulatory requirements while maintaining optimal performance.</p>',
                'icon' => 'shield',
                'is_featured' => false,
                'sort_order' => 7,
                'features' => [
                    'BTRC Compliant Logging',
                    'Real-time IP Tracking',
                    'Automated Report Generation',
                    'High-Performance Storage',
                    'Secure Data Management',
                    'Custom Dashboard',
                ],
            ],
            [
                'title' => 'SMS Solutions',
                'short_description' => 'Bulk SMS and transactional messaging solutions for business communication.',
                'description' => '<h3>Business SMS Solutions</h3><p>Reach your customers instantly with our reliable SMS solutions. We offer bulk SMS, transactional SMS, and OTP services with high delivery rates and competitive pricing.</p>',
                'icon' => 'message',
                'is_featured' => false,
                'sort_order' => 8,
                'features' => [
                    'Bulk SMS Campaigns',
                    'Transactional SMS / OTP',
                    'SMS API Integration',
                    'Delivery Reports & Analytics',
                    'Contact Management',
                    'Scheduled Messaging',
                ],
            ],
        ];

        foreach ($services as $serviceData) {
            $features = $serviceData['features'];
            unset($serviceData['features']);

            $service = Service::create($serviceData);

            foreach ($features as $index => $feature) {
                ServiceFeature::create([
                    'service_id' => $service->id,
                    'title' => $feature,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
