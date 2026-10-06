<?php

use App\Http\Controllers\Account\LearningProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('account')->name('account.')->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::get('profiles', [LearningProfileController::class, 'index'])->name('profiles.index');
    Route::post('profiles', [LearningProfileController::class, 'store'])->name('profiles.store');
    Route::patch('profiles/{profile}', [LearningProfileController::class, 'update'])->name('profiles.update');
    Route::delete('profiles/{profile}', [LearningProfileController::class, 'destroy'])->name('profiles.destroy');
});
