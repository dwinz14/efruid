<?php

namespace Tests\Feature;

use App\Enums\StatusPermohonan;
use Tests\TestCase;

class UiFoundationTest extends TestCase
{
    public function test_setiap_status_punya_kelas_badge_yang_didefinisikan_di_css(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        foreach (StatusPermohonan::cases() as $status) {
            $this->assertStringContainsString(
                '.' . $status->badgeClass() . ' {',
                $css,
                "Kelas {$status->badgeClass()} untuk {$status->value} belum didefinisikan di app.css"
            );
        }
    }

    public function test_tiga_status_pending_dan_selesai_punya_warna_berbeda(): void
    {
        $classes = collect([
            StatusPermohonan::PENDING_ATASAN,
            StatusPermohonan::PENDING_DIRUT,
            StatusPermohonan::PENDING_IT,
            StatusPermohonan::EXECUTED,
        ])->map->badgeClass();

        $this->assertCount(4, $classes->unique());
    }

    public function test_status_badge_menampilkan_label_dan_kelas_status(): void
    {
        $this->blade('<x-status-badge :status="$s" size="md" dot />', ['s' => StatusPermohonan::REJECTED])
            ->assertSee('Ditolak')
            ->assertSee('badge-rejected');
    }

    public function test_status_badge_menerima_nilai_string(): void
    {
        $this->blade('<x-status-badge status="EXECUTED" />')
            ->assertSee('Selesai Dieksekusi')
            ->assertSee('badge-executed');
    }

    public function test_page_header_menampilkan_deskripsi_kembali_dan_aksi(): void
    {
        $this->blade(
            '<x-page-header description="Deskripsi uji" back="/halaman-asal" back-label="Ke daftar">
                <x-slot:actions>Aksi uji</x-slot:actions>
            </x-page-header>'
        )
            ->assertSee('Deskripsi uji')
            ->assertSee('Ke daftar')
            ->assertSee('/halaman-asal', false)
            ->assertSee('Aksi uji');
    }

    public function test_page_header_kosong_tidak_merender_apa_pun(): void
    {
        $this->blade('<x-page-header />')->assertDontSee('<div', false);
    }
}
