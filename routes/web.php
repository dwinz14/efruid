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

    Route::get('/eksekusi', fn() => view('dashboard'))->name('eksekusi.index');
    Route::get('/admin/users', fn() => view('dashboard'))->name('admin.users.index');
    Route::get('/admin/kantors', fn() => view('dashboard'))->name('admin.kantors.index');
    Route::get('/admin/jabatans', fn() => view('dashboard'))->name('admin.jabatans.index');
    Route::get('/admin/audit-logs', fn() => view('dashboard'))->name('admin.audit-logs.index');

    // Approval Atasan
    Route::middleware('role:atasan')->group(function () {
        Route::get('/approval/atasan', [App\Http\Controllers\ApprovalController::class, 'atasanIndex'])
            ->name('approval.atasan.index');
        Route::get('/approval/atasan/{permohonan}', [App\Http\Controllers\ApprovalController::class, 'atasanShow'])
            ->name('approval.atasan.show');
        Route::post('/approval/atasan/{permohonan}/approve', [App\Http\Controllers\ApprovalController::class, 'atasanApprove'])
            ->name('approval.atasan.approve');
        Route::post('/approval/atasan/{permohonan}/reject', [App\Http\Controllers\ApprovalController::class, 'atasanReject'])
            ->name('approval.atasan.reject');
    });

    // Approval Dirut
    Route::middleware('role:dirut')->group(function () {
        Route::get('/approval/dirut', [App\Http\Controllers\ApprovalController::class, 'dirutIndex'])
            ->name('approval.dirut.index');
        Route::get('/approval/dirut/{permohonan}', [App\Http\Controllers\ApprovalController::class, 'dirutShow'])
            ->name('approval.dirut.show');
        Route::post('/approval/dirut/{permohonan}/approve', [App\Http\Controllers\ApprovalController::class, 'dirutApprove'])
            ->name('approval.dirut.approve');
        Route::post('/approval/dirut/{permohonan}/reject', [App\Http\Controllers\ApprovalController::class, 'dirutReject'])
            ->name('approval.dirut.reject');
    });

    // Permohonan
    Route::get('/permohonan', [App\Http\Controllers\PermohonanController::class, 'index'])
        ->name('permohonan.index');
    Route::get('/permohonan/buat', [App\Http\Controllers\PermohonanController::class, 'create'])
        ->name('permohonan.create');
    Route::get('/permohonan/buat/step-2', [App\Http\Controllers\PermohonanController::class, 'createStep2'])
        ->name('permohonan.step2');
    Route::post('/permohonan/buat/step-3', [App\Http\Controllers\PermohonanController::class, 'createStep3'])
        ->name('permohonan.step3');
    Route::post('/permohonan/submit', [App\Http\Controllers\PermohonanController::class, 'submit'])
        ->name('permohonan.submit');
    Route::post('/permohonan/draft', [App\Http\Controllers\PermohonanController::class, 'saveDraft'])
        ->name('permohonan.draft');
    Route::get('/permohonan/{permohonan}', [App\Http\Controllers\PermohonanController::class, 'show'])
        ->name('permohonan.show');
    Route::get('/permohonan/{permohonan}/edit', [App\Http\Controllers\PermohonanController::class, 'edit'])
        ->name('permohonan.edit');
    Route::post('/permohonan/{permohonan}/cancel', [App\Http\Controllers\PermohonanController::class, 'cancel'])
        ->name('permohonan.cancel');
    Route::get('/permohonan/{permohonan}/pdf', [App\Http\Controllers\PermohonanController::class, 'downloadPdf'])
        ->name('permohonan.pdf');
    Route::post('/permohonan/{permohonan}/revise', [App\Http\Controllers\ApprovalController::class, 'revise'])
        ->name('permohonan.revise');

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
    //tanda tangan
    Route::get('/signature/{user}', [App\Http\Controllers\ProfileController::class, 'showSignature'])
        ->name('signature.show');
});
