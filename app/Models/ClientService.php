<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClientService extends Model
{
    protected $fillable = ['user_id','service_id','package_id','label','description','credentials','specs','status','billing_cycle','price','start_date','next_renewal_date','expiry_date','admin_notes'];
    protected $casts = ['credentials' => 'encrypted:array', 'specs' => 'array', 'start_date' => 'date', 'next_renewal_date' => 'date', 'expiry_date' => 'date', 'price' => 'decimal:2'];

    public function user() { return $this->belongsTo(User::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function package() { return $this->belongsTo(Package::class); }
    public function tickets() { return $this->hasMany(Ticket::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
    public function scopeActive($q) { return $q->where('status', 'active'); }
    public function scopeExpiringSoon($q, $days = 30) { return $q->where('status','active')->whereNotNull('next_renewal_date')->where('next_renewal_date','<=',now()->addDays($days)); }
    public function daysUntilRenewal(): ?int { return $this->next_renewal_date ? now()->diffInDays($this->next_renewal_date, false) : null; }
}
