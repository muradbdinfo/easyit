<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ServiceRequestController as AdminServiceRequestController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
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
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect('/admin/dashboard');
        }
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

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
});

require __DIR__.'/auth.php';
