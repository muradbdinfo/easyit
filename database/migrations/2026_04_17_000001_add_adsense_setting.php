<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        // Add Google AdSense setting if it doesn't exist
        if (!Setting::where('key', 'google_adsense_id')->exists()) {
            Setting::create([
                'key' => 'google_adsense_id',
                'value' => null,
                'group' => 'seo',
            ]);
        }
    }

    public function down(): void
    {
        Setting::where('key', 'google_adsense_id')->delete();
    }
};
