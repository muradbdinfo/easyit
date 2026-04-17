<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientServiceController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\InvoiceAdminController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PaymentAdminController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ServiceRequestController as AdminServiceRequestController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\TicketAdminController;
use App\Http\Controllers\Client;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ── Public Routes ──
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{category:slug}', [PostController::class, 'byCategory'])->name('blog.category');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/service-request', [PageController::class, 'serviceRequest'])->name('service-request');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

// ── AdSense Required Legal Pages ──
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');

// ── Rate Limited Form Submissions ──
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
    Route::post('/service-request', [PageController::class, 'submitServiceRequest'])->name('service-request.submit');
});

// ── SEO Routes ──
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path, ['Content-Type' => 'application/xml']);
});

// ── Breeze Default Dashboard ──
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user && $user->hasRole('admin')) return redirect('/admin/dashboard');
    if ($user && $user->hasRole('client')) return redirect('/client/dashboard');
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Admin Routes ──
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('services', AdminServiceController::class);
    Route::resource('projects', AdminProjectController::class);
    Route::resource('team-members', AdminTeamMemberController::class);
    Route::resource('testimonials', AdminTestimonialController::class);
    Route::resource('posts', AdminPostController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('faqs', AdminFaqController::class);
    Route::resource('packages', AdminPackageController::class);
    Route::resource('pages', AdminPageController::class)->only(['index', 'edit', 'update']);

    Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::patch('contacts/{contact}/status', [AdminContactController::class, 'updateStatus'])->name('contacts.updateStatus');
    Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('service-requests', [AdminServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::get('service-requests/{serviceRequest}', [AdminServiceRequestController::class, 'show'])->name('service-requests.show');
    Route::patch('service-requests/{serviceRequest}/status', [AdminServiceRequestController::class, 'updateStatus'])->name('service-requests.updateStatus');

    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // ── Client Portal Management ──
    Route::resource('clients', ClientController::class)->only(['index', 'create', 'store', 'show', 'update']);
    Route::resource('client-services', ClientServiceController::class)->only(['index', 'create', 'store', 'edit', 'update']);

    Route::get('tickets', [TicketAdminController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [TicketAdminController::class, 'show'])->name('tickets.show');
    Route::post('tickets/{ticket}/reply', [TicketAdminController::class, 'reply'])->name('tickets.reply');
    Route::post('tickets/{ticket}/internal-note', [TicketAdminController::class, 'internalNote'])->name('tickets.internalNote');
    Route::patch('tickets/{ticket}/status', [TicketAdminController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::patch('tickets/{ticket}/assign', [TicketAdminController::class, 'assign'])->name('tickets.assign');

    Route::resource('invoices', InvoiceAdminController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('invoices/{invoice}/send', [InvoiceAdminController::class, 'send'])->name('invoices.send');

    Route::get('payments', [PaymentAdminController::class, 'index'])->name('payments.index');
    Route::patch('payments/{payment}/verify', [PaymentAdminController::class, 'verify'])->name('payments.verify');
    Route::patch('payments/{payment}/reject', [PaymentAdminController::class, 'reject'])->name('payments.reject');
});

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

require __DIR__.'/auth.php';
