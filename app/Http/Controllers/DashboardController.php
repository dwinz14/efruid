<?php

namespace App\Http\Controllers;

use App\Enums\StatusPermohonan;
use App\Models\Permohonan;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user()->load('jabatan', 'kantor', 'roles');

        // Data spesifik per role — hanya load yang relevan
        $data = match (true) {
            $user->isSuperAdmin() => $this->superAdminData(),
            $user->isDirut()      => $this->dirutData($user),
            $user->isItStaff()    => $this->itStaffData(),
            default               => $this->pemohonData($user),
        };

        return view('dashboard', array_merge(['user' => $user], $data));
    }

    // ── Super Admin ───────────────────────────────────────────────────────

    private function superAdminData(): array
    {
        $statuses = collect(StatusPermohonan::cases())
            ->mapWithKeys(fn($s) => [
                $s->value => Permohonan::where('status', $s->value)->count(),
            ]);

        // Grafik 6 bulan terakhir
        $bulanIni  = Carbon::now();
        $chartData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanIni->copy()->subMonths($i);
            $chartData->push([
                'label' => $bulan->locale('id')->isoFormat('MMM YY'),
                'count' => Permohonan::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->whereNotIn('status', [
                        StatusPermohonan::DRAFT->value,
                        StatusPermohonan::CANCELLED->value,
                    ])
                    ->count(),
            ]);
        }

        $recentPermohonan = Permohonan::with(['pemohon', 'kantor'])
            ->whereNotIn('status', [StatusPermohonan::DRAFT->value])
            ->latest()
            ->take(5)
            ->get();

        // ── Security KPI ──────────────────────────────────────────────────────
        $onlineThreshold = now()->subMinutes(5)->timestamp;

        $securityKpi = [
            'online_now'         => DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', $onlineThreshold)
                ->count(),
            'locked_accounts'    => User::whereNotNull('locked_at')->count(),
            'suspended_accounts' => User::whereNotNull('suspended_at')->count(),
            'pending_verify'     => User::where('email_verified', false)
                ->where('is_active', true)
                ->count(),
            'failed_today'       => AuditLog::where('aksi', \App\Enums\AksiAudit::USER_LOGIN_FAILED->value)
                ->whereDate('created_at', today())
                ->count(),
            'total_users'        => User::where('is_active', true)->count(),
        ];

        // 5 event security terbaru
        $recentSecurityEvents = AuditLog::with('user')
            ->whereIn('aksi', [
                \App\Enums\AksiAudit::USER_LOGIN_FAILED->value,
                \App\Enums\AksiAudit::USER_ACCOUNT_LOCKED->value,
                \App\Enums\AksiAudit::USER_ACCOUNT_UNLOCKED->value,
                \App\Enums\AksiAudit::USER_SUSPENDED->value,
                \App\Enums\AksiAudit::USER_UNSUSPENDED->value,
                \App\Enums\AksiAudit::USER_FORCE_LOGOUT->value,
                \App\Enums\AksiAudit::USER_LOGOUT_ALL->value,
                \App\Enums\AksiAudit::USER_MANUAL_VERIFIED->value,
            ])
            ->latest('created_at')
            ->take(5)
            ->get();

        return compact(
            'statuses',
            'chartData',
            'recentPermohonan',
            'securityKpi',
            'recentSecurityEvents'
        );
    }

    // ── Dirut ─────────────────────────────────────────────────────────────

    private function dirutData($user): array
    {
        // PENDING_ATASAN di mana atasan_id = dirut ini
        $pendingAsAtasan = Permohonan::where('atasan_id', $user->id)
            ->where('status', StatusPermohonan::PENDING_ATASAN)
            ->count();

        // PENDING_DIRUT semua
        $pendingDirut = Permohonan::where('status', StatusPermohonan::PENDING_DIRUT)
            ->count();

        $totalPending = $pendingAsAtasan + $pendingDirut;

        $recentApproved = Permohonan::with(['pemohon', 'kantor'])
            ->whereHas(
                'approvalLogs',
                fn($q) =>
                $q->where('user_id', $user->id)->where('aksi', 'approved')
            )
            ->latest()
            ->take(5)
            ->get();

        return compact('pendingAsAtasan', 'pendingDirut', 'totalPending', 'recentApproved');
    }

    // ── IT Staff ──────────────────────────────────────────────────────────

    private function itStaffData(): array
    {
        $pendingIt = Permohonan::where('status', StatusPermohonan::PENDING_IT)->count();

        $executedToday = Permohonan::where('status', StatusPermohonan::EXECUTED)
            ->whereDate('updated_at', Carbon::today())
            ->count();

        $executedThisMonth = Permohonan::where('status', StatusPermohonan::EXECUTED)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->count();

        $recentPending = Permohonan::with(['pemohon', 'kantor'])
            ->where('status', StatusPermohonan::PENDING_IT)
            ->latest()
            ->take(5)
            ->get();

        return compact('pendingIt', 'executedToday', 'executedThisMonth', 'recentPending');
    }

    // ── Pemohon (default — juga untuk Atasan yang punya permohonan sendiri) ─

    private function pemohonData($user): array
    {
        $statuses = collect(StatusPermohonan::cases())
            ->mapWithKeys(fn($s) => [
                $s->value => Permohonan::where('pemohon_id', $user->id)
                    ->where('status', $s->value)
                    ->count(),
            ]);

        // Pending sebagai atasan
        $pendingAsAtasan = Permohonan::where('atasan_id', $user->id)
            ->where('status', StatusPermohonan::PENDING_ATASAN)
            ->count();

        $recentPermohonan = Permohonan::where('pemohon_id', $user->id)
            ->with('kantor')
            ->latest()
            ->take(5)
            ->get();

        return compact('statuses', 'pendingAsAtasan', 'recentPermohonan');
    }
}
