@component('mail::message')

# {{ $purpose === 'reset_password' ? 'Reset Password' : 'Verifikasi Email' }}

Halo, **{{ $user->name }}**.

@if($purpose === 'verify_email')
Gunakan kode berikut untuk memverifikasi alamat email Anda di sistem eFRUID BPR Artha Pamenang.
@else
Gunakan kode berikut untuk mereset password akun eFRUID Anda.
@endif

@component('mail::panel')
# {{ $code }}
@endcomponent

Kode berlaku selama **{{ $expireMinutes }} menit** dan hanya dapat digunakan **satu kali**.

Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini. Akun Anda tetap aman.

---
*Sistem eFRUID — BPR Artha Pamenang*
*Email ini dikirim otomatis, harap tidak membalas.*

@endcomponent

