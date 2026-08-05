<?php

use Illuminate\Support\Facades\Route;

// Redirect root ke dashboard (auth middleware akan redirect ke login jika belum auth)
Route::get('/', fn() => redirect()->route('dashboard'));

// Dashboard placeholder
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'email.verified'])
    ->name('dashboard');


// Placeholder routes (diimplementasi per fase)
// Fase 2: auth routes ditangani Breeze
require __DIR__ . '/auth.php';

// Route placeholders agar sidebar tidak error saat build layout
Route::middleware(['auth', 'email.verified'])->group(function () {
    Route::get('/permohonan', fn() => view('dashboard'))->name('permohonan.index');
    Route::get('/approval/atasan', fn() => view('dashboard'))->name('approval.atasan.index');
    Route::get('/approval/dirut', fn() => view('dashboard'))->name('approval.dirut.index');
    Route::get('/eksekusi', fn() => view('dashboard'))->name('eksekusi.index');
    Route::get('/admin/users', fn() => view('dashboard'))->name('admin.users.index');
    Route::get('/admin/kantors', fn() => view('dashboard'))->name('admin.kantors.index');
    Route::get('/admin/jabatans', fn() => view('dashboard'))->name('admin.jabatans.index');
    Route::get('/admin/audit-logs', fn() => view('dashboard'))->name('admin.audit-logs.index');


    //profile user
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('profile.password');
    Route::post('/profile/signature/upload', [App\Http\Controllers\ProfileController::class, 'uploadSignature'])
        ->name('profile.signature.upload');
    Route::post('/profile/signature/canvas', [App\Http\Controllers\ProfileController::class, 'saveSignatureCanvas'])
        ->name('profile.signature.canvas');
    Route::delete('/profile/signature', [App\Http\Controllers\ProfileController::class, 'deleteSignature'])
        ->name('profile.signature.delete');
    Route::get('/signature/{user}', [App\Http\Controllers\ProfileController::class, 'showSignature'])
        ->name('signature.show');
    //tanda tangan
    Route::get('/signature/{user}', [App\Http\Controllers\ProfileController::class, 'showSignature'])
        ->name('signature.show');
});
