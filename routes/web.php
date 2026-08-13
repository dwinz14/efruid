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
    Route::get('/permohonan/{permohonan}/pdf', [App\Http\Controllers\EksekusiController::class, 'downloadPdf'])
        ->name('permohonan.pdf');
    Route::get('/dokumen/{permohonan}/preview', [App\Http\Controllers\DokumenController::class, 'preview'])
        ->name('dokumen.preview');

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

    Route::get('/file/signature', function (Illuminate\Http\Request $request) {
        $path = base64_decode($request->query('path', ''));

        // Validasi: hanya boleh akses file di folder signatures/
        if (! str_starts_with($path, 'signatures/')) {
            abort(403);
        }

        if (! \Illuminate\Support\Facades\Storage::exists($path)) {
            abort(404);
        }

        return response(
            \Illuminate\Support\Facades\Storage::get($path),
            200,
            ['Content-Type' => 'image/png', 'Cache-Control' => 'private, max-age=3600']
        );
    })->name('signature.file');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/count',        [App\Http\Controllers\NotificationController::class, 'count'])
            ->name('count');
        Route::get('/',             [App\Http\Controllers\NotificationController::class, 'index'])
            ->name('index');
        Route::post('/read',        [App\Http\Controllers\NotificationController::class, 'markRead'])
            ->name('read');
        Route::post('/read-all',    [App\Http\Controllers\NotificationController::class, 'markAllRead'])
            ->name('readAll');
    });
});

//admin
Route::middleware(['auth', 'email.verified', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Users
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])
            ->name('users.index');
        Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])
            ->name('users.create');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])
            ->name('users.store');
        Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])
            ->name('users.show');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])
            ->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])
            ->name('users.update');
        Route::post('/users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])
            ->name('users.resetPassword');

        // Kantors
        Route::get('/kantor', [App\Http\Controllers\Admin\KantorController::class, 'index'])
            ->name('kantor.index');
        Route::post('/kantor', [App\Http\Controllers\Admin\KantorController::class, 'store'])
            ->name('kantor.store');
        Route::put('/kantor/{kantor}', [App\Http\Controllers\Admin\KantorController::class, 'update'])
            ->name('kantor.update');
        Route::delete('/kantor/{kantor}', [App\Http\Controllers\Admin\KantorController::class, 'destroy'])
            ->name('kantor.destroy');

        // Jabatans
        Route::get('/jabatan', [App\Http\Controllers\Admin\JabatanController::class, 'index'])
            ->name('jabatan.index');
        Route::post('/jabatan', [App\Http\Controllers\Admin\JabatanController::class, 'store'])
            ->name('jabatan.store');
        Route::put('/jabatan/{jabatan}', [App\Http\Controllers\Admin\JabatanController::class, 'update'])
            ->name('jabatan.update');
        Route::delete('/jabatan/{jabatan}', [App\Http\Controllers\Admin\JabatanController::class, 'destroy'])
            ->name('jabatan.destroy');

        // Audit Logs
        Route::get('/audit-logs', [App\Http\Controllers\Admin\AuditLogController::class, 'index'])
            ->name('audit-logs.index');
        Route::get('/audit-logs/export', [App\Http\Controllers\Admin\AuditLogController::class, 'export'])
            ->name('audit-logs.export');
        Route::get('/audit-logs/{auditLog}', [App\Http\Controllers\Admin\AuditLogController::class, 'show'])
            ->name('audit-logs.show');

        // Semua Permohonan
        Route::get('/permohonan', [App\Http\Controllers\Admin\PermohonanController::class, 'index'])
            ->name('permohonan.index');
        Route::get('/permohonan/{permohonan}', [App\Http\Controllers\Admin\PermohonanController::class, 'show'])
            ->name('permohonan.show');
    });

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

//IT eksekusi FRUID
Route::middleware(['auth', 'email.verified', 'role:it_staff'])->group(function () {
    Route::get('/eksekusi', [App\Http\Controllers\EksekusiController::class, 'index'])
        ->name('eksekusi.index');
    Route::get('/eksekusi/{permohonan}', [App\Http\Controllers\EksekusiController::class, 'show'])
        ->name('eksekusi.show');
    Route::post('/eksekusi/{permohonan}/execute', [App\Http\Controllers\EksekusiController::class, 'execute'])
        ->name('eksekusi.execute');
});
