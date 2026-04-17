<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'comments_enabled',          'value' => '1',  'group' => 'comments'],
            ['key' => 'comments_require_approval',  'value' => '1',  'group' => 'comments'],
            ['key' => 'comments_allow_guests',      'value' => '1',  'group' => 'comments'],
            ['key' => 'comments_max_depth',         'value' => '3',  'group' => 'comments'],
            ['key' => 'comments_per_page',          'value' => '20', 'group' => 'comments'],
            ['key' => 'comments_notify_admin',      'value' => '1',  'group' => 'comments'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }

    public function down(): void
    {
        Setting::whereIn('key', [
            'comments_enabled', 'comments_require_approval', 'comments_allow_guests',
            'comments_max_depth', 'comments_per_page', 'comments_notify_admin',
        ])->delete();
    }
};
