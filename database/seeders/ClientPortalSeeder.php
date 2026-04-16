<?php
namespace Database\Seeders;
use App\Models\User;
use App\Models\Service;
use App\Models\ClientService;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ClientPortalSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'client']);

        $client = User::create([
            'name'=>'Test Client','email'=>'client@easyit.com.bd','phone'=>'+8801712345678',
            'company'=>'ABC Corporation','password'=>Hash::make('password'),'status'=>'active','email_verified_at'=>now(),
        ]);
        $client->assignRole('client');

        $hosting = Service::where('title','Web Hosting')->first();
        if ($hosting) {
            $cs = ClientService::create([
                'user_id'=>$client->id,'service_id'=>$hosting->id,'label'=>'ABC Corp Website Hosting',
                'status'=>'active','billing_cycle'=>'yearly','price'=>5000.00,
                'start_date'=>now()->subMonths(6),'next_renewal_date'=>now()->addMonths(6),
                'specs'=>['disk'=>'10GB SSD','bandwidth'=>'Unlimited','domains'=>5],
            ]);

            $invoice = Invoice::create([
                'user_id'=>$client->id,'client_service_id'=>$cs->id,'subtotal'=>5000,'tax'=>0,'discount'=>0,'total'=>5000,
                'status'=>'sent','issue_date'=>now(),'due_date'=>now()->addDays(15),
            ]);
            $invoice->items()->create(['description'=>'Web Hosting - Yearly Plan','quantity'=>1,'unit_price'=>5000,'total'=>5000]);

            $cat = TicketCategory::where('name','Hosting Support')->first();
            $ticket = Ticket::create([
                'user_id'=>$client->id,'ticket_category_id'=>$cat?->id,'client_service_id'=>$cs->id,
                'subject'=>'Website loading slowly','priority'=>'medium','status'=>'open',
            ]);
            $ticket->messages()->create([
                'user_id'=>$client->id,'body'=>'My website has been loading very slowly since yesterday. Can you please check the server?','is_admin_reply'=>false,
            ]);
        }
    }
}
