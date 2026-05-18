<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LeagueController;
use App\Http\Controllers\Admin\TipController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TelegramController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Bettor\PaymentController;
use App\Http\Controllers\Bettor\TipController as BettorTipController;
use App\Http\Controllers\Bettor\ProfileController as BettorProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicTipController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    if (auth()->user()?->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('bettor.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

// Public Tips Routes
Route::get('/tips', [PublicTipController::class, 'index'])->name('tips.index');
Route::get('/tips/{tip}', [PublicTipController::class, 'show'])->name('tips.show');

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // League Management
    Route::resource('leagues', LeagueController::class);
    Route::patch('leagues/{league}/toggle-status', [LeagueController::class, 'toggleStatus'])
        ->name('leagues.toggle-status');

    // Tip Management
    Route::resource('tips', TipController::class);
    Route::patch('tips/{tip}/update-status', [TipController::class, 'updateStatus'])
        ->name('tips.update-status');
    Route::post('tips/bulk-action', [TipController::class, 'bulkAction'])
        ->name('tips.bulk-action');

    // User Management
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])
        ->name('users.reset-password');
    Route::get('users/export/csv', [UserController::class, 'export'])
        ->name('users.export');

    // Subscription Management
    Route::get('subscriptions', [AdminSubscriptionController::class, 'index'])
        ->name('subscriptions.index');
    Route::get('subscriptions/create', [AdminSubscriptionController::class, 'create'])
        ->name('subscriptions.create');
    Route::post('subscriptions', [AdminSubscriptionController::class, 'store'])
        ->name('subscriptions.store');
    Route::get('subscriptions/{subscription}', [AdminSubscriptionController::class, 'show'])
        ->name('subscriptions.show');
    Route::post('subscriptions/{subscription}/cancel', [AdminSubscriptionController::class, 'cancel'])
        ->name('subscriptions.cancel');
    Route::post('subscriptions/{subscription}/extend', [AdminSubscriptionController::class, 'extend'])
        ->name('subscriptions.extend');
    Route::get('reports/revenue', [AdminSubscriptionController::class, 'revenue'])
        ->name('subscriptions.revenue');
    Route::post('subscriptions/transactions/{transaction}/approve', [AdminSubscriptionController::class, 'approveTransfer'])
        ->name('subscriptions.transfer.approve');
    Route::post('subscriptions/transactions/{transaction}/reject', [AdminSubscriptionController::class, 'rejectTransfer'])
        ->name('subscriptions.transfer.reject');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/subscription', [SettingsController::class, 'subscriptionSettings'])
            ->name('subscription');
        Route::put('/subscription', [SettingsController::class, 'updateSubscriptionSettings'])
            ->name('subscription.update');

        Route::get('/telegram', [TelegramController::class, 'settings'])
            ->name('telegram');
        Route::put('/telegram', [TelegramController::class, 'updateSettings'])
            ->name('telegram.update');
        Route::post('/telegram/test', [TelegramController::class, 'testConnection'])
            ->name('telegram.test');
        Route::post('/telegram/invite-link', [TelegramController::class, 'getInviteLink'])
            ->name('telegram.invite-link');
        Route::post('/telegram/members/{user}/resend-invite', [TelegramController::class, 'resendInvite'])
            ->name('telegram.member.resend-invite');
        Route::put('/telegram/free-group', [TelegramController::class, 'updateFreeGroup'])
            ->name('telegram.free-group.update');
    });

    // Notification Routes
    Route::get('/notifications', [AdminNotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [AdminNotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
});

// Bettor Routes
Route::middleware(['auth', 'verified', 'role:bettor'])->prefix('bettor')->name('bettor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('bettor.dashboard');
    })->name('dashboard');

    // Free Tips (available to all authenticated bettors)
    Route::get('/tips/free', [BettorTipController::class, 'freeTips'])
        ->name('tips.free');

    // Premium Tips (requires subscription)
    Route::middleware('subscription')->group(function () {
        Route::get('/tips/premium', [BettorTipController::class, 'premiumTips'])
            ->name('tips.premium');
    });

    Route::get('/tips/{tip}', [BettorTipController::class, 'show'])
        ->name('tips.show');

    // Profile
    Route::get('/profile', [BettorProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile', [BettorProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [BettorProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    // Payments
    Route::get('/plans', [PaymentController::class, 'plans'])->name('plans');
    Route::get('/payment', [PaymentController::class, 'options'])->name('payment.options');
    Route::post('/payment/initialize', [PaymentController::class, 'initialize'])->name('payment.initialize');
    Route::post('/payment/transfer', [PaymentController::class, 'initializeTransfer'])->name('payment.transfer');
    Route::post('/payment/contact', [PaymentController::class, 'contactEmail'])->name('payment.contact');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/history', [PaymentController::class, 'history'])->name('payment.history');

    // Notification Routes
    Route::get('/notifications', [\App\Http\Controllers\Bettor\NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\Bettor\NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Bettor\NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\Bettor\NotificationController::class, 'unreadCount'])
        ->name('notifications.unread-count');
});

// Shared Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
