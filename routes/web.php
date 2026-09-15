<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\KantorController;
use App\Http\Controllers\Admin\SecurityCenterController;
use App\Http\Controllers\Admin\SessionMonitorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\EksekusiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerifikasiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'email.verified'])
    ->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware(['auth', 'email.verified'])->group(function () {

    // Permohonan
    Route::get('/permohonan', [PermohonanController::class, 'index'])
        ->name('permohonan.index');
    Route::get('/permohonan/buat', [PermohonanController::class, 'create'])
        ->name('permohonan.create');
    Route::get('/permohonan/buat/step-2', [PermohonanController::class, 'createStep2'])
        ->name('permohonan.step2');
    Route::post('/permohonan/buat/step-3', [PermohonanController::class, 'createStep3'])
        ->name('permohonan.step3');
    Route::post('/permohonan/submit', [PermohonanController::class, 'submit'])
        ->name('permohonan.submit');
    Route::post('/permohonan/draft', [PermohonanController::class, 'saveDraft'])
        ->name('permohonan.draft');
    Route::get('/permohonan/{permohonan}', [PermohonanController::class, 'show'])
        ->name('permohonan.show');
    Route::get('/permohonan/{permohonan}/edit', [PermohonanController::class, 'edit'])
        ->name('permohonan.edit');
    Route::post('/permohonan/{permohonan}/cancel', [PermohonanController::class, 'cancel'])
        ->name('permohonan.cancel');
    Route::post('/permohonan/{permohonan}/revise', [ApprovalController::class, 'revise'])
        ->name('permohonan.revise');
    Route::get('/permohonan/{permohonan}/pdf', [EksekusiController::class, 'downloadPdf'])
        ->name('permohonan.pdf');
    Route::get('/dokumen/{permohonan}/preview', [DokumenController::class, 'preview'])
        ->middleware('secure.doc')
        ->name('dokumen.preview');
    Route::get('/dokumen/preview/session', [DokumenController::class, 'previewFromSession'])
        ->middleware('secure.doc')
        ->name('dokumen.preview-session');

    // profile user
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/count', [NotificationController::class, 'count'])
            ->name('count');
        Route::get('/', [NotificationController::class, 'index'])
            ->name('index');
        Route::post('/read', [NotificationController::class, 'markRead'])
            ->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllRead'])
            ->name('readAll');
    });
});

// admin
Route::middleware(['auth', 'email.verified', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Users
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');
        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');
        Route::get('/users/pending', [UserController::class, 'pending'])
            ->name('users.pending');
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.resetPassword');
        Route::post('/users/{user}/manual-verify', [UserController::class, 'manualVerify'])
            ->name('users.manualVerify');
        Route::post('/users/{user}/reject-registration', [UserController::class, 'rejectRegistration'])
            ->name('users.rejectRegistration');
        Route::post('/users/{user}/suspend', [UserController::class, 'suspend'])
            ->name('users.suspend');
        Route::post('/users/{user}/unsuspend', [UserController::class, 'unsuspend'])
            ->name('users.unsuspend');
        Route::post('/users/{user}/lock', [UserController::class, 'lock'])
            ->name('users.lock');
        Route::post('/users/{user}/unlock', [UserController::class, 'unlock'])
            ->name('users.unlock');
        Route::post('/users/{user}/force-logout', [UserController::class, 'forceLogout'])
            ->name('users.forceLogout');
        Route::post('/users/{user}/force-logout-all', [UserController::class, 'forceLogoutAll'])
            ->name('users.forceLogoutAll');

        // Session Monitoring
        Route::get('/sessions', [SessionMonitorController::class, 'index'])
            ->name('sessions.index');
        Route::get('/security', [SecurityCenterController::class, 'index'])
            ->name('security.index');
        Route::get('/security/login-history', [SecurityCenterController::class, 'loginHistory'])
            ->name('security.login-history');

        // Kantors
        Route::get('/kantor', [KantorController::class, 'index'])
            ->name('kantor.index');
        Route::post('/kantor', [KantorController::class, 'store'])
            ->name('kantor.store');
        Route::put('/kantor/{kantor}', [KantorController::class, 'update'])
            ->name('kantor.update');
        Route::delete('/kantor/{kantor}', [KantorController::class, 'destroy'])
            ->name('kantor.destroy');

        // Jabatans
        Route::get('/jabatan', [JabatanController::class, 'index'])
            ->name('jabatan.index');
        Route::post('/jabatan', [JabatanController::class, 'store'])
            ->name('jabatan.store');
        Route::put('/jabatan/{jabatan}', [JabatanController::class, 'update'])
            ->name('jabatan.update');
        Route::delete('/jabatan/{jabatan}', [JabatanController::class, 'destroy'])
            ->name('jabatan.destroy');

        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');
        Route::get('/audit-logs/export', [AuditLogController::class, 'export'])
            ->name('audit-logs.export');
        Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])
            ->name('audit-logs.show');

        // Semua Permohonan
        Route::get('/permohonan', [App\Http\Controllers\Admin\PermohonanController::class, 'index'])
            ->name('permohonan.index');
        Route::get('/permohonan/{permohonan}', [App\Http\Controllers\Admin\PermohonanController::class, 'show'])
            ->name('permohonan.show');
    });

// Approval Atasan
Route::middleware(['auth', 'email.verified', 'role:atasan'])->group(function () {
    Route::get('/approval/atasan', [ApprovalController::class, 'atasanIndex'])
        ->name('approval.atasan.index');
    Route::get('/approval/atasan/riwayat', [ApprovalController::class, 'atasanRiwayat'])
        ->name('approval.atasan.riwayat');
    Route::get('/approval/atasan/{permohonan}', [ApprovalController::class, 'atasanShow'])
        ->name('approval.atasan.show');
    Route::post('/approval/atasan/{permohonan}/approve', [ApprovalController::class, 'atasanApprove'])
        ->name('approval.atasan.approve');
    Route::post('/approval/atasan/{permohonan}/reject', [ApprovalController::class, 'atasanReject'])
        ->name('approval.atasan.reject');
});

// Approval Dirut
Route::middleware(['auth', 'email.verified', 'role:dirut'])->group(function () {
    Route::get('/approval/dirut', [ApprovalController::class, 'dirutIndex'])
        ->name('approval.dirut.index');
    Route::get('/approval/dirut/riwayat', [ApprovalController::class, 'dirutRiwayat'])
        ->name('approval.dirut.riwayat');
    Route::get('/approval/dirut/{permohonan}', [ApprovalController::class, 'dirutShow'])
        ->name('approval.dirut.show');
    Route::post('/approval/dirut/{permohonan}/approve-as-atasan', [ApprovalController::class, 'dirutApproveAsAtasan'])
        ->name('approval.dirut.approveAsAtasan');
    Route::post('/approval/dirut/{permohonan}/approve', [ApprovalController::class, 'dirutApprove'])
        ->name('approval.dirut.approve');
    Route::post('/approval/dirut/{permohonan}/reject', [ApprovalController::class, 'dirutReject'])
        ->name('approval.dirut.reject');
});

// IT eksekusi FRUID
Route::middleware(['auth', 'email.verified', 'role:it_staff'])->group(function () {
    Route::get('/eksekusi', [EksekusiController::class, 'index'])
        ->name('eksekusi.index');
    Route::get('/eksekusi/riwayat', [EksekusiController::class, 'riwayat'])
        ->name('eksekusi.riwayat');
    Route::get('/eksekusi/{permohonan}', [EksekusiController::class, 'show'])
        ->name('eksekusi.show');
    Route::post('/eksekusi/{permohonan}/execute', [EksekusiController::class, 'execute'])
        ->name('eksekusi.execute');
    Route::post('/eksekusi/{permohonan}/claim', [EksekusiController::class, 'claim'])
        ->name('eksekusi.claim');
    Route::post('/eksekusi/{permohonan}/unclaim', [EksekusiController::class, 'unclaim'])
        ->name('eksekusi.unclaim');
});

// ── Verifikasi Dokumen Publik (tanpa login, rate-limited) ─────────────────
Route::get('/verify/{token}', [VerifikasiController::class, 'show'])
    ->middleware(['throttle:20,1', 'secure.doc:deny'])
    ->name('verifikasi.show')
    ->where('token', '[a-f0-9]{32}');

Route::get('/verify/{token}/doc', [VerifikasiController::class, 'document'])
    ->middleware(['throttle:60,1', 'secure.doc'])
    ->name('verifikasi.document')
    ->where('token', '[a-f0-9]{32}');
