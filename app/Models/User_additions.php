<?php
// ADD these relationships to your existing User model (app/Models/User.php):

// Add these use statements at top:
// use App\Models\ClientService;
// use App\Models\Ticket;
// use App\Models\Invoice;
// use App\Models\Payment;

// Add these methods inside the User class:

public function clientServices()
{
    return $this->hasMany(ClientService::class);
}

public function tickets()
{
    return $this->hasMany(Ticket::class);
}

public function invoices()
{
    return $this->hasMany(Invoice::class);
}

public function payments()
{
    return $this->hasMany(Payment::class);
}

public function isClient(): bool
{
    return !$this->hasRole('admin');
}
