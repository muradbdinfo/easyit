<?php

namespace App\Console\Commands;

use App\Models\PageView;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncPageViewCount extends Command
{
    protected $signature = 'pageviews:sync';

    protected $description = 'Resync the cached page view counter with the database';

    public function handle(): int
    {
        $count = PageView::count();
        Cache::forever('page_views_total', $count);

        $this->info("Page view cache synced: {$count} total views.");

        return self::SUCCESS;
    }
}
