<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AksiAudit;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SecurityCenterController extends Controller
{
    // ── Security Center Dashboard ─────────────────────────────────────────

    public function index(): View
    {
        $onlineThreshold = now()->subMinutes(5)->timestamp;
        $idleThreshold   = now()->subMinutes(30)->timestamp;

        // KPI cards
        $kpi = [
            'online_now'         => DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', $onlineThreshold)
                ->count(),
            'idle_now'           => DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '<', $onlineThreshold)
                ->where('last_activity', '>=', $idleThreshold)
                ->count(),
            'total_sessions'     => DB::table('sessions')->whereNotNull('user_id')->count(),
            'locked_accounts'    => User::whereNotNull('locked_at')->count(),
            'suspended_accounts' => User::whereNotNull('suspended_at')->count(),
            'pending_verify'     => User::where('email_verified', false)
                ->where('is_active', true)
                ->count(),
            'failed_today'       => AuditLog::where('aksi', AksiAudit::USER_LOGIN_FAILED->value)
                ->whereDate('created_at', today())
                ->count(),
            'failed_this_week'   => AuditLog::where('aksi', AksiAudit::USER_LOGIN_FAILED->value)
                ->where('created_at', '>=', now()->startOfWeek())
                ->count(),
        ];

        // Gagal login per jam dalam 24 jam terakhir (untuk mini chart)
        $failedPerHour = AuditLog::where('aksi', AksiAudit::USER_LOGIN_FAILED->value)
            ->where('created_at', '>=', now()->subHours(24))
            ->selectRaw("HOUR(created_at) as jam, count(*) as total")
            ->groupByRaw("HOUR(created_at)")
            ->orderBy('jam')
            ->pluck('total', 'jam');

        // Akun terkunci — ditampilkan sebagai daftar
        $lockedUsers = User::with(['kantor', 'roles'])
            ->whereNotNull('locked_at')
            ->orderByDesc('locked_at')
            ->get();

        // Akun suspended
        $suspendedUsers = User::with(['kantor', 'roles'])
            ->whereNotNull('suspended_at')
            ->orderByDesc('suspended_at')
            ->get();

        // 20 event keamanan terbaru
        $recentEvents = AuditLog::with('user')
            ->whereIn('aksi', [
                AksiAudit::USER_LOGIN_FAILED->value,
                AksiAudit::USER_ACCOUNT_LOCKED->value,
                AksiAudit::USER_ACCOUNT_UNLOCKED->value,
                AksiAudit::USER_SUSPENDED->value,
                AksiAudit::USER_UNSUSPENDED->value,
                AksiAudit::USER_FORCE_LOGOUT->value,
                AksiAudit::USER_LOGOUT_ALL->value,
                AksiAudit::USER_MANUAL_VERIFIED->value,
                AksiAudit::USER_REJECTED->value,
            ])
            ->latest('created_at')
            ->take(20)
            ->get();

        // User dengan failed_login_count > 0, diurutkan tertinggi
        $atRiskUsers = User::with(['kantor'])
            ->where('failed_login_count', '>', 0)
            ->orderByDesc('failed_login_count')
            ->take(10)
            ->get();

        return view('admin.security.index', compact(
            'kpi',
            'failedPerHour',
            'lockedUsers',
            'suspendedUsers',
            'recentEvents',
            'atRiskUsers'
        ));
    }

    // ── Login History ─────────────────────────────────────────────────────

    public function loginHistory(Request $request): View
    {
        $query = AuditLog::with('user')
            ->whereIn('aksi', [
                AksiAudit::USER_LOGIN->value,
                AksiAudit::USER_LOGIN_FAILED->value,
                AksiAudit::USER_LOGOUT->value,
            ])
            ->latest('created_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%' . $request->ip . '%');
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $logs  = $query->paginate(50)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name']);

        $aksiOptions = [
            AksiAudit::USER_LOGIN,
            AksiAudit::USER_LOGIN_FAILED,
            AksiAudit::USER_LOGOUT,
        ];

        // Summary counts sesuai filter aktif
        $summaryQuery = AuditLog::whereIn('aksi', [
            AksiAudit::USER_LOGIN->value,
            AksiAudit::USER_LOGIN_FAILED->value,
            AksiAudit::USER_LOGOUT->value,
        ]);
        if ($request->filled('user_id')) $summaryQuery->where('user_id', $request->user_id);
        if ($request->filled('dari'))    $summaryQuery->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai'))  $summaryQuery->whereDate('created_at', '<=', $request->sampai);

        $summary = $summaryQuery
            ->selectRaw('aksi, count(*) as total')
            ->groupBy('aksi')
            ->pluck('total', 'aksi');

        return view('admin.security.login-history', compact(
            'logs',
            'users',
            'aksiOptions',
            'summary'
        ));
    }
}
