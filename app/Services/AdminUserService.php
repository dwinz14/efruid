<?php

namespace App\Services;

use App\Enums\AksiAudit;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminUserService
{
    // ── Manual Verification ───────────────────────────────────────────────

    public function manualVerify(User $target, User $actor, string $reason): void
    {
        $before = ['email_verified' => $target->email_verified];

        $target->update(['email_verified' => true]);

        AuditService::log(
            AksiAudit::USER_MANUAL_VERIFIED,
            $actor->id,
            $target,
            $before,
            ['email_verified' => true, 'reason' => $reason],
        );
    }

    // ── Suspend / Unsuspend ───────────────────────────────────────────────

    public function suspend(User $target, User $actor, string $reason): void
    {
        $before = ['suspended_at' => null, 'suspension_reason' => null];

        $target->update([
            'suspended_at'      => now(),
            'suspension_reason' => $reason,
        ]);

        // Invalidasi semua session aktif
        $this->invalidateAllSessions($target);

        AuditService::log(
            AksiAudit::USER_SUSPENDED,
            $actor->id,
            $target,
            $before,
            ['suspended_at' => now()->toDateTimeString(), 'reason' => $reason],
        );
    }

    public function unsuspend(User $target, User $actor): void
    {
        $before = [
            'suspended_at'      => $target->suspended_at?->toDateTimeString(),
            'suspension_reason' => $target->suspension_reason,
        ];

        $target->update([
            'suspended_at'      => null,
            'suspension_reason' => null,
        ]);

        AuditService::log(
            AksiAudit::USER_UNSUSPENDED,
            $actor->id,
            $target,
            $before,
            ['suspended_at' => null],
        );
    }

    // ── Activate / Deactivate ─────────────────────────────────────────────

    public function activate(User $target, User $actor): void
    {
        $before = ['is_active' => $target->is_active];

        $target->update(['is_active' => true]);

        AuditService::log(
            AksiAudit::USER_ACTIVATED,
            $actor->id,
            $target,
            $before,
            ['is_active' => true],
        );
    }

    public function deactivate(User $target, User $actor): void
    {
        $before = ['is_active' => $target->is_active];

        $target->update(['is_active' => false]);

        $this->invalidateAllSessions($target);

        AuditService::log(
            AksiAudit::USER_DEACTIVATED,
            $actor->id,
            $target,
            $before,
            ['is_active' => false],
        );
    }

    // ── Lock / Unlock ─────────────────────────────────────────────────────

    public function lock(User $target, User $actor, string $reason): void
    {
        $before = ['locked_at' => null];

        $target->update(['locked_at' => now()]);

        $this->invalidateAllSessions($target);

        AuditService::log(
            AksiAudit::USER_ACCOUNT_LOCKED,
            $actor->id,
            $target,
            $before,
            ['locked_at' => now()->toDateTimeString(), 'reason' => $reason],
        );
    }

    public function unlock(User $target, User $actor): void
    {
        $before = ['locked_at' => $target->locked_at?->toDateTimeString()];

        $target->update([
            'locked_at'          => null,
            'failed_login_count' => 0,
        ]);

        AuditService::log(
            AksiAudit::USER_ACCOUNT_UNLOCKED,
            $actor->id,
            $target,
            $before,
            ['locked_at' => null, 'failed_login_count' => 0],
        );
    }

    // ── Force Logout ──────────────────────────────────────────────────────

    public function forceLogoutSession(User $target, User $actor, string $sessionId, string $reason): void
    {
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $target->id)
            ->delete();

        AuditService::log(
            AksiAudit::USER_FORCE_LOGOUT,
            $actor->id,
            $target,
            null,
            ['session_id' => $sessionId, 'reason' => $reason],
        );
    }

    public function forceLogoutAll(User $target, User $actor, string $reason): void
    {
        $sessionCount = DB::table('sessions')
            ->where('user_id', $target->id)
            ->count();

        $this->invalidateAllSessions($target);

        AuditService::log(
            AksiAudit::USER_LOGOUT_ALL,
            $actor->id,
            $target,
            null,
            ['sessions_terminated' => $sessionCount, 'reason' => $reason],
        );
    }

    // ── Private Helpers ───────────────────────────────────────────────────

    private function invalidateAllSessions(User $target): void
    {
        DB::table('sessions')
            ->where('user_id', $target->id)
            ->delete();
    }
}
