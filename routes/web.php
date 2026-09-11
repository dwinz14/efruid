<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', App\Http\Controllers\DashboardController::class)
    ->middleware(['auth', 'email.verified'])
    ->name('dashboard');

require __DIR__ . '/auth.php';


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
    Route::post('/permohonan/{permohonan}/revise', [App\Http\Controllers\ApprovalController::class, 'revise'])
        ->name('permohonan.revise');
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
        Route::get('/users/pending', [App\Http\Controllers\Admin\UserController::class, 'pending'])
            ->name('users.pending');
        Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])
            ->name('users.show');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])
            ->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])
            ->name('users.update');
        Route::post('/users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])
            ->name('users.resetPassword');
        Route::post('/users/{user}/manual-verify',      [App\Http\Controllers\Admin\UserController::class, 'manualVerify'])
            ->name('users.manualVerify');
        Route::post('/users/{user}/reject-registration', [App\Http\Controllers\Admin\UserController::class, 'rejectRegistration'])
            ->name('users.rejectRegistration');
        Route::post('/users/{user}/suspend',            [App\Http\Controllers\Admin\UserController::class, 'suspend'])
            ->name('users.suspend');
        Route::post('/users/{user}/unsuspend',          [App\Http\Controllers\Admin\UserController::class, 'unsuspend'])
            ->name('users.unsuspend');
        Route::post('/users/{user}/lock',               [App\Http\Controllers\Admin\UserController::class, 'lock'])
            ->name('users.lock');
        Route::post('/users/{user}/unlock',             [App\Http\Controllers\Admin\UserController::class, 'unlock'])
            ->name('users.unlock');
        Route::post('/users/{user}/force-logout',       [App\Http\Controllers\Admin\UserController::class, 'forceLogout'])
            ->name('users.forceLogout');
        Route::post('/users/{user}/force-logout-all',   [App\Http\Controllers\Admin\UserController::class, 'forceLogoutAll'])
            ->name('users.forceLogoutAll');

        // Session Monitoring
        Route::get('/sessions',                         [App\Http\Controllers\Admin\SessionMonitorController::class, 'index'])
            ->name('sessions.index');
        Route::get('/security', [App\Http\Controllers\Admin\SecurityCenterController::class, 'index'])
            ->name('security.index');
        Route::get('/security/login-history', [App\Http\Controllers\Admin\SecurityCenterController::class, 'loginHistory'])
            ->name('security.login-history');

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
Route::middleware(['auth', 'email.verified', 'role:atasan'])->group(function () {
    Route::get('/approval/atasan', [App\Http\Controllers\ApprovalController::class, 'atasanIndex'])
        ->name('approval.atasan.index');
    Route::get('/approval/atasan/riwayat', [App\Http\Controllers\ApprovalController::class, 'atasanRiwayat'])
        ->name('approval.atasan.riwayat');
    Route::get('/approval/atasan/{permohonan}', [App\Http\Controllers\ApprovalController::class, 'atasanShow'])
        ->name('approval.atasan.show');
    Route::post('/approval/atasan/{permohonan}/approve', [App\Http\Controllers\ApprovalController::class, 'atasanApprove'])
        ->name('approval.atasan.approve');
    Route::post('/approval/atasan/{permohonan}/reject', [App\Http\Controllers\ApprovalController::class, 'atasanReject'])
        ->name('approval.atasan.reject');
});

// Approval Dirut
Route::middleware(['auth', 'email.verified', 'role:dirut'])->group(function () {
    Route::get('/approval/dirut', [App\Http\Controllers\ApprovalController::class, 'dirutIndex'])
        ->name('approval.dirut.index');
    Route::get('/approval/dirut/riwayat', [App\Http\Controllers\ApprovalController::class, 'dirutRiwayat'])
        ->name('approval.dirut.riwayat');
    Route::get('/approval/dirut/{permohonan}', [App\Http\Controllers\ApprovalController::class, 'dirutShow'])
        ->name('approval.dirut.show');
    Route::post('/approval/dirut/{permohonan}/approve-as-atasan', [App\Http\Controllers\ApprovalController::class, 'dirutApproveAsAtasan'])
        ->name('approval.dirut.approveAsAtasan');
    Route::post('/approval/dirut/{permohonan}/approve', [App\Http\Controllers\ApprovalController::class, 'dirutApprove'])
        ->name('approval.dirut.approve');
    Route::post('/approval/dirut/{permohonan}/reject', [App\Http\Controllers\ApprovalController::class, 'dirutReject'])
        ->name('approval.dirut.reject');
});

//IT eksekusi FRUID
Route::middleware(['auth', 'email.verified', 'role:it_staff'])->group(function () {
    Route::get('/eksekusi', [App\Http\Controllers\EksekusiController::class, 'index'])
        ->name('eksekusi.index');
    Route::get('/eksekusi/riwayat', [App\Http\Controllers\EksekusiController::class, 'riwayat'])
        ->name('eksekusi.riwayat');
    Route::get('/eksekusi/{permohonan}', [App\Http\Controllers\EksekusiController::class, 'show'])
        ->name('eksekusi.show');
    Route::post('/eksekusi/{permohonan}/execute', [App\Http\Controllers\EksekusiController::class, 'execute'])
        ->name('eksekusi.execute');
    Route::post('/eksekusi/{permohonan}/claim', [App\Http\Controllers\EksekusiController::class, 'claim'])
        ->name('eksekusi.claim');
    Route::post('/eksekusi/{permohonan}/unclaim', [App\Http\Controllers\EksekusiController::class, 'unclaim'])
        ->name('eksekusi.unclaim');
});

// ── Verifikasi Dokumen Publik (tanpa login, rate-limited) ─────────────────
Route::get('/verify/{token}', [App\Http\Controllers\VerifikasiController::class, 'show'])
    ->middleware(['throttle:20,1'])
    ->name('verifikasi.show')
    ->where('token', '[a-f0-9]{32}');

Route::get('/verify/{token}/doc', [App\Http\Controllers\VerifikasiController::class, 'document'])
    ->middleware(['throttle:60,1'])
    ->name('verifikasi.document')
    ->where('token', '[a-f0-9]{32}');
