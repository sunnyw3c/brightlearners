<?php

use App\Http\Controllers\Account\LearningProfileController;
use App\Http\Controllers\Account\LibraryController;
use App\Http\Controllers\Account\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('account')->name('account.')->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::get('library', [LibraryController::class, 'index'])->name('library.index');
    Route::get('library/{resource:slug}', [LibraryController::class, 'show'])->name('library.show');

    Route::get('profiles', [LearningProfileController::class, 'index'])->name('profiles.index');
    Route::post('profiles', [LearningProfileController::class, 'store'])->name('profiles.store');
    Route::patch('profiles/{profile}', [LearningProfileController::class, 'update'])->name('profiles.update');
    Route::delete('profiles/{profile}', [LearningProfileController::class, 'destroy'])->name('profiles.destroy');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
