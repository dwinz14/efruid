<?php

namespace Database\Seeders;

use App\Enums\RoleUser;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUserSeeder extends Seeder
{
    public function run(): void
    {
        $kantorPusat = Kantor::where('is_pusat', true)->firstOrFail();
        $jabatanDirut = Jabatan::where('nama', 'DIREKTUR UTAMA')->firstOrFail();

        // ── GANTI: isi data Super Admin sesuai kebutuhan sebelum deploy ──
        $superAdmin = User::updateOrCreate(
            ['email' => 'super@admin.com'], // GANTI
            [
                'name'           => 'SUPER ADMIN',           // GANTI
                'nik'            => 'AP000000000',            // GANTI: format AP + 9 digit
                'password'       => Hash::make('superadmin123'), // GANTI
                'kantor_id'      => $kantorPusat->id,
                'jabatan_id'     => $jabatanDirut->id,
                'is_active'      => true,
                'email_verified' => true,
            ]
        );

        $superAdminRole = Role::where('name', RoleUser::SUPER_ADMIN->value)->firstOrFail();
        $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);

        $this->command->info('✓ Super Admin seeder selesai. Harap ganti kredensial di InitialUserSeeder.php sebelum deploy!');
    }
}
