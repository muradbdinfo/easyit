<?php
// ADD THIS to routes/web.php BEFORE the `require __DIR__.'/auth.php';` line

use App\Http\Controllers\Client;

// ── Client Portal Routes ──
Route::middleware(['auth', 'verified', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [Client\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [Client\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Client\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/services', [Client\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{clientService}', [Client\ServiceController::class, 'show'])->name('services.show');

    Route::get('/tickets', [Client\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [Client\TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [Client\TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [Client\TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [Client\TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/rate', [Client\TicketController::class, 'rate'])->name('tickets.rate');

    Route::get('/invoices', [Client\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [Client\InvoiceController::class, 'show'])->name('invoices.show');

    Route::get('/invoices/{invoice}/pay', [Client\PaymentController::class, 'create'])->name('payments.create');
    Route::post('/invoices/{invoice}/pay', [Client\PaymentController::class, 'store'])->name('payments.store');
});

// ── Admin Portal Management (ADD inside existing admin route group) ──
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientServiceController;
use App\Http\Controllers\Admin\TicketAdminController;
use App\Http\Controllers\Admin\InvoiceAdminController;
use App\Http\Controllers\Admin\PaymentAdminController;

// ADD these inside the existing admin Route::group:
// Route::resource('clients', ClientController::class)->only(['index','create','store','show','update']);
// Route::resource('client-services', ClientServiceController::class)->only(['index','create','store','edit','update']);
//
// Route::get('tickets', [TicketAdminController::class, 'index'])->name('tickets.index');
// Route::get('tickets/{ticket}', [TicketAdminController::class, 'show'])->name('tickets.show');
// Route::post('tickets/{ticket}/reply', [TicketAdminController::class, 'reply'])->name('tickets.reply');
// Route::post('tickets/{ticket}/internal-note', [TicketAdminController::class, 'internalNote'])->name('tickets.internalNote');
// Route::patch('tickets/{ticket}/status', [TicketAdminController::class, 'updateStatus'])->name('tickets.updateStatus');
// Route::patch('tickets/{ticket}/assign', [TicketAdminController::class, 'assign'])->name('tickets.assign');
//
// Route::resource('invoices', InvoiceAdminController::class)->only(['index','create','store','show']);
// Route::post('invoices/{invoice}/send', [InvoiceAdminController::class, 'send'])->name('invoices.send');
//
// Route::get('payments', [PaymentAdminController::class, 'index'])->name('payments.index');
// Route::patch('payments/{payment}/verify', [PaymentAdminController::class, 'verify'])->name('payments.verify');
// Route::patch('payments/{payment}/reject', [PaymentAdminController::class, 'reject'])->name('payments.reject');
