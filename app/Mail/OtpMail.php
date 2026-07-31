<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User   $user,
        public readonly string $code,
        public readonly string $purpose,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->purpose) {
            'verify_email'   => 'Kode Verifikasi Email — eFRUID',
            'reset_password' => 'Kode Reset Password — eFRUID',
            default          => 'Kode OTP — eFRUID',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.otp', with: [
            'user'    => $this->user,
            'code'    => $this->code,
            'purpose' => $this->purpose,
            'expireMinutes' => 10,
        ]);
    }
}
