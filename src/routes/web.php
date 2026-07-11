<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Customer\TicketController as CustomerTicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Technician\ProfileController as TechnicianProfileController;
use App\Http\Controllers\Technician\TicketController as TechnicianTicketController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Illuminate\Support\Facades\Response;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/* END */

// =========================================================
// Public routes
// =========================================================

Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'CUSTOMER'   => redirect()->route('customer.dashboard'),
            'TECHNICIAN' => redirect()->route('technician.dashboard'),
            default      => redirect('/admin'),
        };
    }
    return app(HomeController::class)->index();
})->name('home');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update')->middleware('guest');

// =========================================================
// Customer routes
// =========================================================

Route::middleware(['auth', 'role:CUSTOMER'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerTicketController::class, 'dashboard'])->name('dashboard');

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/',         [CustomerTicketController::class, 'index'])->name('index');
        Route::get('/create',   [CustomerTicketController::class, 'create'])->name('create');
        Route::post('/',        [CustomerTicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [CustomerTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/rate', [CustomerTicketController::class, 'rate'])->name('rate');
    });
});

// =========================================================
// Technician routes
// =========================================================

Route::middleware(['auth', 'role:TECHNICIAN'])->prefix('technician')->name('technician.')->group(function () {
    Route::get('/dashboard', [TechnicianTicketController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets',   [TechnicianTicketController::class, 'index'])->name('tickets');

    Route::get('/profile',  [TechnicianProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',  [TechnicianProfileController::class, 'update'])->name('profile.update');

    Route::prefix('tickets')->name('ticket.')->group(function () {
        Route::get('/{ticket}',           [TechnicianTicketController::class, 'show'])->name('show');
        Route::patch('/{ticket}/status',  [TechnicianTicketController::class, 'updateStatus'])->name('update-status');
    });
});
