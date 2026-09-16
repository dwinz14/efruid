<?php

namespace App\Services;

use Carbon\Carbon;

class SealService
{
    // ── Canvas ────────────────────────────────────────────────────────────
    private const IMG_W = 160;
    private const IMG_H = 215;

    // ── Hanko circle — center & radii ─────────────────────────────────────
    private const CX        = 80;  // center X
    private const CY        = 80;  // center Y
    private const R_FILL    = 68;  // 1) isi penuh dengan warna seal
    private const R_GAP_OUT = 65;  // 2) potong → terbentuk ring luar 3px
    private const R_RING    = 62;  // 3) isi ring dalam
    private const R_GAP_IN  = 58;  // 4) potong → terbentuk ring dalam 4px; interior putih

    // ── Status cap box ────────────────────────────────────────────────────
    private const CAP_TOP = 158;   // jarak dari atas canvas
    private const CAP_H   = 52;    // tinggi box
    private const CAP_MX  = 16;    // margin horizontal

    // ── Font sizes (dalam pt) ─────────────────────────────────────────────
    private const FS_NAME   = 8;
    private const FS_CODE   = 7;
    private const FS_STATUS = 8;
    private const FS_DATE   = 7;

    // ── Role → warna (R, G, B) ────────────────────────────────────────────
    private const ROLE_COLORS = [
        'Pemohon'            => [26,  82, 118],  // biru tua
        'Atasan'             => [20,  90,  50],  // hijau tua
        'Direktur Utama'     => [20,  90,  50],  // hijau tua
        'Administrator USSI' => [123, 36,  28],  // merah tua
    ];

    private const DEFAULT_COLOR = [26, 82, 118];

    // ─────────────────────────────────────────────────────────────────────
    // PUBLIC API
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Generate Personal Digital Seal dari data stamp.
     *
     * @param  array  $stamp  { role, nama, jabatan, timestamp, hash }
     * @return string  base64 data URI  →  data:image/png;base64,...
     */
    public function generate(array $stamp): string
    {
        $role      = $stamp['role']      ?? 'User';
        $nama      = $stamp['nama']      ?? '—';
        $timestamp = $stamp['timestamp'] ?? '';
        $hash      = $stamp['hash']      ?? '';

        [$r, $g, $b] = self::ROLE_COLORS[$role] ?? self::DEFAULT_COLOR;

        // ── Canvas ────────────────────────────────────────────────────────
        $img   = imagecreatetruecolor(self::IMG_W, self::IMG_H);
        $cSeal = imagecolorallocate($img, $r, $g, $b);
        $cWht  = imagecolorallocate($img, 255, 255, 255);

        imagefill($img, 0, 0, $cWht);   // background putih (kompatibel dompdf)

        // ── Double-ring hanko circle ──────────────────────────────────────
        $this->fillCircle($img, self::R_FILL,    $cSeal);
        $this->fillCircle($img, self::R_GAP_OUT, $cWht);
        $this->fillCircle($img, self::R_RING,    $cSeal);
        $this->fillCircle($img, self::R_GAP_IN,  $cWht);

        // ── Nama (uppercase) ───────────────────────────────────
        $font  = $this->fontPath();
        $lines = $this->splitName($nama);
        $lineH = self::FS_NAME + 4;
        $totalH = count($lines) * $lineH;
        $nameY0 = self::CY - ($totalH / 2) + self::FS_NAME; // baseline baris pertama

        foreach ($lines as $i => $line) {
            $w = $this->textWidth($font, self::FS_NAME, $line);
            $x = (int) (self::CX - $w / 2);
            $y = (int) ($nameY0 + $i * $lineH);
            imagettftext($img, self::FS_NAME, 0, $x, $y, $cSeal, $font, $line);
        }

        // ── Garis pemisah tipis ───────────────────────────────────────────
        $sepY = self::CY + self::R_GAP_IN - 18;
        $sepR = (int) (self::R_GAP_IN * 0.55);
        imageline($img, self::CX - $sepR, $sepY, self::CX + $sepR, $sepY, $cSeal);

        // ── Kode verifikasi (8 karakter awal hash) ────────────────────────
        $code  = strtoupper(substr($hash, 0, 8));
        $codeW = $this->textWidth($font, self::FS_CODE, $code);
        $codeX = (int) (self::CX - $codeW / 2);
        $codeY = self::CY + self::R_GAP_IN - 6;
        imagettftext($img, self::FS_CODE, 0, $codeX, $codeY, $cSeal, $font, $code);

        // ── Status cap box ────────────────────────────────────────────────
        $x1 = self::CAP_MX;
        $x2 = self::IMG_W - self::CAP_MX;
        $y1 = self::CAP_TOP;
        $y2 = self::CAP_TOP + self::CAP_H;

        imagerectangle($img, $x1,     $y1,     $x2,     $y2,     $cSeal);
        imagerectangle($img, $x1 + 2, $y1 + 2, $x2 - 2, $y2 - 2, $cSeal);

        // Label status
        $label  = $this->statusLabel($role);
        $labelW = $this->textWidth($font, self::FS_STATUS, $label);
        imagettftext(
            $img,
            self::FS_STATUS,
            0,
            (int) (self::CX - $labelW / 2),
            $y1 + 16,
            $cSeal,
            $font,
            $label
        );

        // Tanggal
        $dateStr = $this->formatDate($timestamp);
        $dateW   = $this->textWidth($font, self::FS_DATE, $dateStr);
        imagettftext(
            $img,
            self::FS_DATE,
            0,
            (int) (self::CX - $dateW / 2),
            $y1 + 30,
            $cSeal,
            $font,
            $dateStr
        );

        // Waktu
        $timeStr = $this->formatTime($timestamp);
        $timeW   = $this->textWidth($font, self::FS_DATE, $timeStr);
        imagettftext(
            $img,
            self::FS_DATE,
            0,
            (int) (self::CX - $timeW / 2),
            $y1 + 43,
            $cSeal,
            $font,
            $timeStr
        );

        // ── Capture ke base64 ─────────────────────────────────────────────
        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return 'data:image/png;base64,' . base64_encode($png);
    }

    // ─────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────

    /** Gambar lingkaran penuh terpusat pada (CX, CY). */
    private function fillCircle($img, int $radius, $color): void
    {
        imagefilledellipse($img, self::CX, self::CY, $radius * 2, $radius * 2, $color);
    }

    /** Hitung lebar teks TTF. */
    private function textWidth(string $font, float $size, string $text): int
    {
        $bbox = imagettfbbox($size, 0, $font, $text);
        return abs($bbox[2] - $bbox[0]);
    }

    /** Path absolut ke font TTF. */
    private function fontPath(): string
    {
        $path = base_path('resources/fonts/DejaVuSans-Bold.ttf');

        if (! file_exists($path)) {
            throw new \RuntimeException(
                "SealService: Font tidak ditemukan di [{$path}]. " .
                    "Jalankan: cp vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf resources/fonts/"
            );
        }

        return $path;
    }

    /**
     * Pecah nama menjadi maksimal 2 baris uppercase
     */
    private function splitName(string $nama): array
    {
        $maxChars = 14;

        // Normalisasi nama
        $nama = strtoupper(trim($nama));
        $words = preg_split('/\s+/', $nama, -1, PREG_SPLIT_NO_EMPTY);

        if (empty($words)) {
            return ['', '', ''];
        }

        //  Kalau hanya 1 kata
        if (count($words) === 1) {
            return [
                substr($words[0], 0, $maxChars),
                '',
                '',
            ];
        }

        // Cari pembagian 3 baris yang paling balance.
        $best = null;
        $bestScore = PHP_INT_MAX;
        $count = count($words);

        for ($i = 1; $i < $count; $i++) {
            for ($j = $i + 1; $j <= $count; $j++) {

                $line1 = implode(' ', array_slice($words, 0, $i));
                $line2 = implode(' ', array_slice($words, $i, $j - $i));
                $line3 = implode(' ', array_slice($words, $j));

                // Kalau line 3 kosong, tetap valid untuk nama 2 kata
                if ($line3 === '') {
                    $line3 = '';
                }

                // Jangan gunakan kombinasi yang melebihi batas
                if (
                    strlen($line1) > $maxChars ||
                    strlen($line2) > $maxChars ||
                    strlen($line3) > $maxChars
                ) {
                    continue;
                }

                $lengths = [
                    strlen($line1),
                    strlen($line2),
                    strlen($line3),
                ];

                // Hitung score berdasarkan perbedaan panjang baris
                $maxLength = max($lengths);
                $minLength = min(
                    array_filter($lengths, fn($length) => $length > 0)
                );

                $score = $maxLength - $minLength;

                if ($score < $bestScore) {
                    $bestScore = $score;
                    $best = [$line1, $line2, $line3];
                }
            }
        }

        // fallback dengan membagi secara aman.
        if ($best === null) {
            $lines = ['', '', ''];
            $lineIndex = 0;

            foreach ($words as $word) {
                if ($lineIndex >= 3) {
                    break;
                }

                // Kata lebih panjang dari batas → potong
                if (strlen($word) > $maxChars) {
                    $remaining = $word;

                    while ($remaining !== '' && $lineIndex < 3) {
                        $lines[$lineIndex] = substr($remaining, 0, $maxChars);
                        $remaining = substr($remaining, $maxChars);
                        $lineIndex++;
                    }

                    continue;
                }

                // Masukkan ke baris saat ini jika muat
                $candidate = $lines[$lineIndex] === ''
                    ? $word
                    : $lines[$lineIndex] . ' ' . $word;

                if (strlen($candidate) <= $maxChars) {
                    $lines[$lineIndex] = $candidate;
                } else {
                    $lineIndex++;

                    if ($lineIndex < 3) {
                        $lines[$lineIndex] = $word;
                    }
                }
            }

            return $lines;
        }

        return $best;
    }


    /** Label teks di dalam status cap box. */
    private function statusLabel(string $role): string
    {
        return match ($role) {
            'Pemohon'            => 'PEMOHON',
            'Atasan'             => 'DISETUJUI',
            'Direktur Utama'     => 'DISETUJUI',
            'Administrator USSI' => 'DIEKSEKUSI',
            default              => 'TERVERIFIKASI',
        };
    }

    /**
     * Ambil bagian tanggal dari timestamp.
     * Input: "08/09/2026 14:30:00 WIB" → "8 Sep 2026"
     */
    private function formatDate(string $ts): string
    {
        try {
            $clean = trim(str_replace(' WIB', '', $ts));
            return Carbon::createFromFormat('d/m/Y H:i:s', $clean)
                ->locale('id')
                ->isoFormat('D MMM YYYY');
        } catch (\Throwable) {
            return substr($ts, 0, 10);
        }
    }

    /**
     * Ambil bagian waktu dari timestamp.
     * Input: "08/09/2026 14:30:00 WIB" → "14:30 WIB"
     */
    private function formatTime(string $ts): string
    {
        $parts = explode(' ', trim($ts));

        if (isset($parts[1])) {
            $hm = implode(':', array_slice(explode(':', $parts[1]), 0, 2));
            return $hm . ' WIB';
        }

        return '';
    }
}
