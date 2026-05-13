<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\OrganizerDashboardController;
use App\Http\Controllers\Dashboard\CustomerDashboardController;
use App\Http\Controllers\Dashboard\ScannerDashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [HomeController::class, 'show'])->name('events.show');

/*
|--------------------------------------------------------------------------
| Authenticated Route: Role-Based Redirect Hub
| After login, the AuthenticatedSessionController redirects here.
| This route inspects the user's role and forwards to the correct dashboard.
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();

    if ($user->hasRole('Super Admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('Event Organizer')) {
        return redirect()->route('organizer.dashboard');
    } elseif ($user->hasRole('Gate Scanner')) {
        return redirect()->route('gate.dashboard');
    } else {
        return redirect()->route('customer.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Super Admin Routes  →  /admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:Super Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Event Organizer Routes  →  /organizer
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:Event Organizer'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {
        Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', EventController::class);
        Route::resource('events.ticket-categories', TicketCategoryController::class)->except(['index', 'show']);
    });

/*
|--------------------------------------------------------------------------
| Customer Routes  →  /customer
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:Customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Checkout Routes (Protected - Customer Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:Customer'])
    ->group(function () {
        Route::get('/checkout/{ticketCategory}', [CheckoutController::class, 'create'])->name('checkout.create');
        Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.store');
    });

/*
|--------------------------------------------------------------------------
| Gate Scanner Routes  →  /gate
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:Gate Scanner'])
    ->prefix('gate')
    ->name('gate.')
    ->group(function () {
        Route::get('/dashboard', [ScannerDashboardController::class, 'index'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Shared Authenticated Routes (Profile – accessible by all roles)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
