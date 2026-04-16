<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['ticket_number','user_id','ticket_category_id','client_service_id','assigned_to','subject','priority','status','satisfaction_rating','satisfaction_feedback','first_response_at','resolved_at','closed_at'];
    protected $casts = ['first_response_at'=>'datetime','resolved_at'=>'datetime','closed_at'=>'datetime'];

    protected static function booted(): void {
        static::creating(function (Ticket $t) {
            if (!$t->ticket_number) {
                $year = now()->format('Y');
                $last = static::whereYear('created_at', $year)->orderByDesc('id')->first();
                $next = $last ? intval(substr($last->ticket_number, -4)) + 1 : 1;
                $t->ticket_number = "EIT-{$year}" . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(TicketCategory::class, 'ticket_category_id'); }
    public function clientService() { return $this->belongsTo(ClientService::class); }
    public function assignedAdmin() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function messages() { return $this->hasMany(TicketMessage::class)->orderBy('created_at'); }
    public function latestMessage() { return $this->hasOne(TicketMessage::class)->latestOfMany(); }
    public function scopeOpen($q) { return $q->whereNotIn('status', ['resolved','closed']); }
    public function scopeForClient($q, $userId) { return $q->where('user_id', $userId); }

    public function getStatusColorAttribute(): string {
        return match($this->status) { 'open'=>'blue','in_progress'=>'yellow','waiting_on_client'=>'orange','waiting_on_admin'=>'purple','resolved'=>'green','closed'=>'gray',default=>'gray' };
    }
    public function getPriorityColorAttribute(): string {
        return match($this->priority) { 'low'=>'gray','medium'=>'blue','high'=>'orange','urgent'=>'red',default=>'gray' };
    }
}
