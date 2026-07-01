<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\OrganizerDashboardController;
use App\Http\Controllers\Dashboard\CustomerDashboardController;
use App\Http\Controllers\Dashboard\ScannerDashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminOrganizerController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganizerProfileController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [HomeController::class, 'show'])->name('events.show');
Route::get('/api/search-events', [HomeController::class, 'searchSuggestions'])->name('api.search.events');

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
    } elseif ($user->hasRole('Customer')) {
        return redirect()->route('customer.dashboard');
    } else {
        return redirect()->route('home');
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
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::get('/organizers', [AdminOrganizerController::class, 'index'])->name('organizers.index');
        Route::post('/organizers/{profile}/verify', [AdminOrganizerController::class, 'verify'])->name('organizers.verify');
        Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
        Route::patch('/events/{event}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
        Route::patch('/events/{event}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::patch('/withdrawals/{withdrawal}/complete', [WithdrawalController::class, 'complete'])->name('withdrawals.complete');
        Route::resource('roles', AdminRoleController::class);
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
        Route::put('/profile', [OrganizerProfileController::class, 'update'])->name('profile.update');
        Route::get('/withdrawals', [WithdrawalController::class, 'organizerIndex'])->name('withdrawals.index');
        Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdrawals.store');
        Route::resource('events', EventController::class);
        Route::resource('events.ticket-categories', TicketCategoryController::class)->except(['index', 'show']);
        Route::resource('scanners', \App\Http\Controllers\Organizer\GateScannerController::class)->except(['show', 'edit', 'update']);
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
        Route::get('/checkout/{order}/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
        Route::post('/checkout/{order}/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
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
        Route::post('/scan', [ScannerController::class, 'verify'])->name('scan');
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

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
});

require __DIR__.'/auth.php';
