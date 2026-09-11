<?php

namespace App\Enums;

enum AksiAudit: string
{
    // Auth
    case USER_REGISTER        = 'user.register';
    case USER_OTP_SENT        = 'user.otp_sent';
    case USER_OTP_VERIFIED    = 'user.otp_verified';
    case USER_OTP_FAILED      = 'user.otp_failed';
    case USER_LOGIN           = 'user.login';
    case USER_LOGIN_FAILED    = 'user.login_failed';
    case USER_LOGOUT          = 'user.logout';
    case USER_PASSWORD_RESET  = 'user.password_reset';
    case USER_SIGNATURE       = 'user.signature_uploaded';
    case USER_ROLE_ASSIGNED   = 'user.role_assigned';
    case USER_CREATED           = 'user.created';
    case USER_MANUAL_VERIFIED   = 'user.manual_verified';
    case USER_ACTIVATED         = 'user.activated';
    case USER_DEACTIVATED       = 'user.deactivated';
    case USER_SUSPENDED         = 'user.suspended';
    case USER_UNSUSPENDED       = 'user.unsuspended';
    case USER_ACCOUNT_LOCKED    = 'user.account_locked';
    case USER_ACCOUNT_UNLOCKED  = 'user.account_unlocked';
    case USER_FORCE_LOGOUT      = 'user.force_logout';
    case USER_LOGOUT_ALL        = 'user.logout_all';
    case USER_REJECTED          = 'user.rejected';

        // Permohonan
    case PERMOHONAN_CREATED   = 'permohonan.created';
    case PERMOHONAN_SUBMITTED = 'permohonan.submitted';
    case PERMOHONAN_CANCELLED = 'permohonan.cancelled';
    case PERMOHONAN_APPROVED  = 'permohonan.approved';
    case PERMOHONAN_REJECTED  = 'permohonan.rejected';
    case PERMOHONAN_REVISED   = 'permohonan.revised';
    case PERMOHONAN_EXECUTED  = 'permohonan.executed';
    case PERMOHONAN_CLAIMED   = 'permohonan.claimed';
    case PERMOHONAN_UNCLAIMED = 'permohonan.unclaimed';

        // PDF
    case PDF_GENERATED        = 'pdf.generated';

        // Verifikasi publik
    case DOKUMEN_DIVERIFIKASI = 'dokumen.diverifikasi';

    public function label(): string
    {
        return match ($this) {
            self::USER_REGISTER       => 'Registrasi akun',
            self::USER_OTP_SENT       => 'OTP dikirim',
            self::USER_OTP_VERIFIED   => 'OTP diverifikasi',
            self::USER_OTP_FAILED     => 'OTP gagal',
            self::USER_LOGIN          => 'Login berhasil',
            self::USER_LOGIN_FAILED   => 'Login gagal',
            self::USER_LOGOUT         => 'Logout',
            self::USER_PASSWORD_RESET => 'Reset password',
            self::USER_SIGNATURE      => 'Upload tanda tangan',
            self::USER_ROLE_ASSIGNED  => 'Perubahan role',
            self::PERMOHONAN_CREATED  => 'Permohonan dibuat',
            self::PERMOHONAN_SUBMITTED => 'Permohonan disubmit',
            self::PERMOHONAN_CANCELLED => 'Permohonan dibatalkan',
            self::PERMOHONAN_APPROVED  => 'Permohonan disetujui',
            self::PERMOHONAN_REJECTED  => 'Permohonan ditolak',
            self::PERMOHONAN_REVISED   => 'Permohonan direvisi',
            self::PERMOHONAN_EXECUTED  => 'Permohonan dieksekusi',
            self::PERMOHONAN_CLAIMED   => 'Permohonan diklaim IT',
            self::PERMOHONAN_UNCLAIMED => 'Klaim permohonan dilepas',
            self::PDF_GENERATED        => 'PDF digenerate',
            self::USER_CREATED          => 'User dibuat oleh admin',
            self::USER_MANUAL_VERIFIED  => 'Verifikasi manual oleh admin',
            self::USER_ACTIVATED        => 'Akun diaktifkan',
            self::USER_DEACTIVATED      => 'Akun dinonaktifkan',
            self::USER_SUSPENDED        => 'Akun disuspend',
            self::USER_UNSUSPENDED      => 'Suspend akun dicabut',
            self::USER_ACCOUNT_LOCKED   => 'Akun dikunci',
            self::USER_ACCOUNT_UNLOCKED => 'Kunci akun dibuka',
            self::USER_FORCE_LOGOUT     => 'Force logout oleh admin',
            self::USER_LOGOUT_ALL       => 'Logout semua sesi oleh admin',
            self::USER_REJECTED         => 'Registrasi ditolak oleh admin',
            self::DOKUMEN_DIVERIFIKASI => 'Dokumen diverifikasi via QR publik',
        };
    }
}
