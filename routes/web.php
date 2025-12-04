<?php

use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\InvoiceFrontController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AvatarTestController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');

// Event routes
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/list', [EventController::class, 'index'])->name('list'); // Alias for backward compatibility
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    
    // Registration routes - requires authentication and email verification
    Route::middleware(['auth', 'smart.verified'])->group(function () {
        Route::get('/{event}/register', [EventController::class, 'showRegistrationForm'])->name('register.form');
        Route::post('/{event}/register', [EventController::class, 'register'])->name('register');
    });
});

// Payment routes
Route::prefix('payment')->name('payment.')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\PaymentController::class, 'index'])->name('index');
    Route::get('/{invoice}', [\App\Http\Controllers\PaymentController::class, 'show'])->name('show');
    Route::post('/{registration}/upload', [\App\Http\Controllers\PaymentController::class, 'uploadProof'])->name('upload');
});

// Invoice routes
Route::prefix('invoice')->name('invoice.')->middleware(['auth'])->group(function () {
    Route::get('/{invoice}', [InvoiceFrontController::class, 'show'])->name('show');
    Route::get('/{invoice}/download', [InvoiceFrontController::class, 'download'])->name('download');
});

// Activities routes
Route::prefix('activities')->name('activities.')->middleware(['auth'])->group(function () {
    Route::post('/clear', [ActivityController::class, 'clear'])->name('clear');
});

Route::get('/about', [App\Http\Controllers\About\AboutController::class, 'index'])->name('about');

// Dashboard routes
Route::middleware(['auth', 'smart.verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

// Admin Dashboard (separate route)
Route::middleware(['auth', 'smart.admin.verified'])->group(function () {
    Route::get('/admin-dashboard', [App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Certificate routes
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('index');
        Route::get('/{certificate}', [CertificateController::class, 'show'])->name('show');
        Route::get('/{certificate}/preview', [CertificateController::class, 'previewTemplate'])->name('preview');
        Route::get('/{certificate}/download', [CertificateController::class, 'download'])->name('download');
        Route::get('/template/{certificate}', [CertificateController::class, 'previewTemplate'])->name('template.preview');
    });
    
    // Materi Belajar routes
    Route::prefix('materi')->name('materi.')->group(function () {
        Route::get('/', [MateriController::class, 'index'])->name('index');
        Route::get('/{materi}/download', [MateriController::class, 'download'])->name('download');
    });
    
    // Ticket routes
    Route::prefix('ticket')->name('ticket.')->group(function () {
        Route::get('/{registration}/download', [TicketController::class, 'download'])->name('download');
    });
});

// Google OAuth routes
Route::prefix('auth/google')->name('google.')->group(function () {
    Route::get('/redirect', [GoogleController::class, 'redirect'])->name('redirect');
    Route::get('/callback', [GoogleController::class, 'callback'])->name('callback');
});

// Avatar test route (development only)
Route::get('/avatar-test', [AvatarTestController::class, 'index'])->name('avatar.test');

require __DIR__.'/auth.php';
// Public certificate verification
Route::get('verify-certificate/{number}', [CertificateController::class, 'verify'])->name('certificate.verify');
