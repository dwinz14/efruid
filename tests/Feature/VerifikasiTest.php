<?php

namespace Tests\Feature;

use App\Enums\AccessLevel;
use App\Enums\FormType;
use App\Enums\JenisPermohonan;
use App\Enums\RoleUser;
use App\Enums\StatusPermohonan;
use App\Models\Kantor;
use App\Models\Permohonan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerifikasiTest extends TestCase
{
    use RefreshDatabase;

    private Permohonan $executedPermohonan;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $kantor = Kantor::create([
            'nama'      => 'PUSAT',
            'kode'      => 'PST',
            'is_pusat'  => true,
            'is_active' => true,
        ]);

        $rolePemohon = Role::create(['name' => RoleUser::PEMOHON->value, 'label' => 'Pemohon']);

        $pemohon = User::create([
            'name'           => 'Jane Doe',
            'nik'            => 'AP123456789',
            'email'          => 'jane@example.com',
            'password'       => 'password',
            'kantor_id'      => $kantor->id,
            'is_active'      => true,
            'email_verified' => true,
        ]);
        $pemohon->roles()->attach($rolePemohon->id);

        $this->token = '8e919f9f47fe971c57cd1ace9c8f5fc6';

        $this->executedPermohonan = Permohonan::create([
            'nomor_dokumen'       => 'FRUID/101/2026/0001',
            'form_type'           => FormType::NORMAL,
            'tanggal_permohonan'  => now(),
            'pemohon_id'          => $pemohon->id,
            'kantor_id'           => $kantor->id,
            'nama_pemohon'        => 'Jane Doe',
            'jabatan_pemohon'     => 'Customer Service',
            'nik_pemohon'         => 'AP123456789',
            'user_id_ussi'        => 'AP1234',
            'jenis_permohonan'    => JenisPermohonan::PENDAFTARAN,
            'access_level'        => AccessLevel::USER,
            'status'              => StatusPermohonan::EXECUTED,
            'verifikasi_token'    => $this->token,
            'verification_stamps' => [
                [
                    'role'      => 'Pemohon',
                    'nama'      => 'Jane Doe',
                    'jabatan'   => 'Customer Service',
                    'timestamp' => '10/09/2026 16:00:00 WIB',
                    'hash'      => 'fd16011cf8e70e1cbb7fdef9a28d0ca17ca512da6de5b0d0104f522fe5ffdbe4',
                ],
                [
                    'role'      => 'Administrator USSI',
                    'nama'      => 'IT Admin',
                    'jabatan'   => 'Staff IT',
                    'timestamp' => '10/09/2026 16:15:00 WIB',
                    'hash'      => 'be63ac8e91fe27faa57fcebe5d4ac6755afee4f32270a632ff12129e07cefeb9',
                ],
            ],
        ]);
    }

    public function test_verification_page_loads_with_valid_token(): void
    {
        $response = $this->get(route('verifikasi.show', ['token' => $this->token]));

        $response->assertOk();
        $response->assertSee('FRUID/101/2026/0001');
        $response->assertSee('Jane Doe');
        $response->assertSee('Customer Service');
        $response->assertSee('Terverifikasi');
        $response->assertSee('Memverifikasi Dokumen'); // Loader markup exists
        $response->assertSee('verificationApp');

        // Verify CSP allows necessary sources without breaking
        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("frame-ancestors 'none'", $csp);
        $this->assertStringContainsString("default-src", $csp);
    }

    public function test_document_iframe_endpoint_loads(): void
    {
        $response = $this->get(route('verifikasi.document', ['token' => $this->token]));

        $response->assertOk();
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertSee('FRUID/101/2026/0001');
        $response->assertSee('Jane Doe');
    }

    public function test_verification_returns_404_for_invalid_token(): void
    {
        $response = $this->get('/verify/00000000000000000000000000000000');

        $response->assertNotFound();
        $response->assertSee('Dokumen Tidak Ditemukan');
    }
}
