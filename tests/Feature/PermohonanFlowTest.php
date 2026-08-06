<?php

namespace Tests\Feature;

use App\Enums\RoleUser;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\Permohonan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PermohonanFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $pemohon;

    private User $atasan;

    protected function setUp(): void
    {
        parent::setUp();

        $kantor = Kantor::create(['nama' => 'PUSAT', 'kode' => 'PST', 'is_pusat' => true, 'is_active' => true]);
        $jabatan = Jabatan::create(['nama' => 'Staff', 'urutan' => 1, 'is_lainnya' => false, 'is_active' => true]);

        $roleAtasan = Role::create(['name' => RoleUser::ATASAN->value, 'label' => 'Atasan']);
        $rolePemohon = Role::create(['name' => RoleUser::PEMOHON->value, 'label' => 'Pemohon']);

        $this->pemohon = User::create([
            'name' => 'Budi Pemohon',
            'nik' => '3500000000000001',
            'email' => 'budi@example.com',
            'password' => 'password',
            'kantor_id' => $kantor->id,
            'jabatan_id' => $jabatan->id,
            'is_active' => true,
            'email_verified' => true,
        ]);
        $this->pemohon->roles()->attach($rolePemohon->id);

        $this->atasan = User::create([
            'name' => 'Atasan Budi',
            'nik' => '3500000000000002',
            'email' => 'atasan@example.com',
            'password' => 'password',
            'kantor_id' => $kantor->id,
            'jabatan_id' => $jabatan->id,
            'is_active' => true,
            'email_verified' => true,
        ]);
        $this->atasan->roles()->attach($roleAtasan->id);
    }

    public function test_index_page_renders(): void
    {
        $this->actingAs($this->pemohon)
            ->get(route('permohonan.index'))
            ->assertOk()
            ->assertSee('Buat Permohonan');
    }

    public function test_step1_page_renders_clickable_cards(): void
    {
        $this->actingAs($this->pemohon)
            ->get(route('permohonan.create'))
            ->assertOk()
            ->assertSee(route('permohonan.step2', ['form_type' => 'normal']), false)
            ->assertSee(route('permohonan.step2', ['form_type' => 'rangkap']), false);
    }

    public function test_step2_page_renders(): void
    {
        $this->actingAs($this->pemohon)
            ->get(route('permohonan.step2', ['form_type' => 'normal']))
            ->assertOk()
            ->assertSee('User ID (USSI)')
            ->assertSee('Atasan');
    }

    public function test_draft_can_be_saved_with_partial_data(): void
    {
        $this->actingAs($this->pemohon)
            ->post(route('permohonan.draft'), [
                'form_type' => 'normal',
                'kantor_id' => $this->pemohon->kantor_id,
                'user_id_ussi' => 'AP0001',
                'jenis_permohonan' => 'pendaftaran',
                'access_level' => 'USER',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('permohonan', [
            'pemohon_id' => $this->pemohon->id,
            'status' => 'DRAFT',
        ]);
    }

    public function test_full_wizard_submit_and_pdf(): void
    {
        Storage::fake('local');

        // Step 3: preview (membuat draft)
        $this->actingAs($this->pemohon)
            ->post(route('permohonan.step3'), [
                'form_type' => 'rangkap',
                'kantor_id' => $this->pemohon->kantor_id,
                'user_id_ussi' => 'AP0001',
                'jenis_permohonan' => 'perubahan',
                'tipe_perubahan' => 'sementara',
                'jabatan_lama' => 'Staff',
                'jabatan_baru' => 'Supervisor',
                'alasan_perubahan' => 'Promosi',
                'tgl_mulai' => '2026-08-01',
                'tgl_selesai' => '2026-12-31',
                'access_level' => 'USER',
                'atasan_id' => $this->atasan->id,
            ])
            ->assertOk()
            ->assertSee('Preview Dokumen');

        $draft = Permohonan::where('pemohon_id', $this->pemohon->id)->firstOrFail();
        $this->assertSame('DRAFT', $draft->status->value);

        // Submit
        $this->actingAs($this->pemohon)
            ->post(route('permohonan.submit'), ['permohonan_id' => $draft->id])
            ->assertRedirect(route('permohonan.show', $draft));

        $permohonan = $draft->fresh();
        $this->assertSame('PENDING_ATASAN', $permohonan->status->value);
        $this->assertNotNull($permohonan->nomor_dokumen);
        $this->assertNotNull($permohonan->pdf_path);
        Storage::disk('local')->assertExists($permohonan->pdf_path);

        // Show page
        $this->actingAs($this->pemohon)
            ->get(route('permohonan.show', $permohonan))
            ->assertOk()
            ->assertSee('Menunggu Atasan');

        // Download PDF
        $this->actingAs($this->pemohon)
            ->get(route('permohonan.pdf', $permohonan))
            ->assertOk();
    }

    public function test_submit_rejects_incomplete_data(): void
    {
        $this->actingAs($this->pemohon)
            ->post(route('permohonan.step3'), [
                'form_type' => 'normal',
                'kantor_id' => $this->pemohon->kantor_id,
                'user_id_ussi' => 'AP0001',
                'jenis_permohonan' => 'pendaftaran',
                'access_level' => 'USER',
            ])
            ->assertSessionHasErrors('atasan_id');
    }
}
