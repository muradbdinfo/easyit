<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['invoice_number','user_id','client_service_id','subtotal','tax','discount','total','status','issue_date','due_date','paid_date','notes','admin_notes','pdf_path'];
    protected $casts = ['subtotal'=>'decimal:2','tax'=>'decimal:2','discount'=>'decimal:2','total'=>'decimal:2','issue_date'=>'date','due_date'=>'date','paid_date'=>'date'];

    protected static function booted(): void {
        static::creating(function (Invoice $inv) {
            if (!$inv->invoice_number) {
                $year = now()->format('Y');
                $last = static::whereYear('created_at', $year)->orderByDesc('id')->first();
                $next = $last ? intval(substr($last->invoice_number, -5)) + 1 : 1;
                $inv->invoice_number = "EIT-INV-{$year}" . str_pad($next, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function clientService() { return $this->belongsTo(ClientService::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function getTotalPaidAttribute(): float { return $this->payments()->where('status','verified')->sum('amount'); }
    public function getBalanceDueAttribute(): float { return $this->total - $this->total_paid; }
    public function isOverdue(): bool { return $this->due_date->isPast() && !in_array($this->status, ['paid','cancelled','refunded']); }
    public function scopeUnpaid($q) { return $q->whereIn('status', ['sent','overdue','partially_paid']); }
    public function scopeForClient($q, $userId) { return $q->where('user_id', $userId); }
}
