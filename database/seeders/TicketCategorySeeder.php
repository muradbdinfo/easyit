<?php
namespace Database\Seeders;
use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            ['name'=>'Billing & Payments','color'=>'#10B981','sort_order'=>1],
            ['name'=>'Technical Support','color'=>'#3B82F6','sort_order'=>2],
            ['name'=>'Domain Issues','color'=>'#8B5CF6','sort_order'=>3],
            ['name'=>'Hosting Support','color'=>'#F59E0B','sort_order'=>4],
            ['name'=>'Network Issues','color'=>'#EF4444','sort_order'=>5],
            ['name'=>'General Inquiry','color'=>'#6B7280','sort_order'=>6],
        ];
        foreach ($cats as $c) TicketCategory::firstOrCreate(['name'=>$c['name']], $c);
    }
}
