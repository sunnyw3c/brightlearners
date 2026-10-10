<?php

use App\Http\Controllers\Account\DashboardController;
use App\Http\Controllers\Account\LearningProfileController;
use App\Http\Controllers\Account\LibraryController;
use App\Http\Controllers\Account\MembershipController;
use App\Http\Controllers\Account\NotificationController;
use App\Http\Controllers\Account\OrderController;
use App\Http\Controllers\Account\ProgressController;
use App\Http\Controllers\Account\WeeklyLearningController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('account')->name('account.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('profiles/{profile}/switch', [DashboardController::class, 'switchProfile'])->name('profiles.switch');

    Route::get('library', [LibraryController::class, 'index'])->name('library.index');
    Route::get('library/{resource:slug}', [LibraryController::class, 'show'])->name('library.show');

    Route::get('profiles', [LearningProfileController::class, 'index'])->name('profiles.index');
    Route::post('profiles', [LearningProfileController::class, 'store'])->name('profiles.store');
    Route::patch('profiles/{profile}', [LearningProfileController::class, 'update'])->name('profiles.update');
    Route::delete('profiles/{profile}', [LearningProfileController::class, 'destroy'])->name('profiles.destroy');
    Route::get('profiles/{profile}/weekly-learning', [WeeklyLearningController::class, 'showProfile'])->name('profiles.weekly-learning');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('membership', [MembershipController::class, 'index'])->name('membership');
    Route::get('weekly-learning', [WeeklyLearningController::class, 'index'])->name('weekly-learning');

    Route::get('progress', [ProgressController::class, 'index'])->name('progress');
    Route::post('progress', [ProgressController::class, 'store'])->name('progress.store');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});
