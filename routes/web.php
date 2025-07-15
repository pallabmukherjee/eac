<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/super', [SuperAdminDashboardController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/admin', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/account', [AdminDashboardController::class, 'account'])->name('admin.account');

});

Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');

require __DIR__.'/auth.php';
