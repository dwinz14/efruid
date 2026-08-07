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

        // Gunakan jabatan default selain Dirut untuk user biasa
        $jabatanDefault = Jabatan::where('nama', '!=', 'DIREKTUR UTAMA')->first()
            ?? $jabatanDirut;

        $users = [
            [
                'role' => RoleUser::SUPER_ADMIN,
                'email' => 'super@admin.com',
                'name' => 'SUPER ADMIN',
                'nik' => 'AP000000001',
                'password' => 'superadmin123',
                'jabatan_id' => $jabatanDirut->id,
            ],
            [
                'role' => RoleUser::PEMOHON,
                'email' => 'pemohon@example.com',
                'name' => 'USER PEMOHON',
                'nik' => 'AP000000002',
                'password' => 'password123',
                'jabatan_id' => $jabatanDefault->id,
            ],
            [
                'role' => RoleUser::ATASAN,
                'email' => 'atasan@example.com',
                'name' => 'USER ATASAN',
                'nik' => 'AP000000003',
                'password' => 'password123',
                'jabatan_id' => $jabatanDefault->id,
            ],
            [
                'role' => RoleUser::DIRUT,
                'email' => 'dirut@example.com',
                'name' => 'DIREKTUR UTAMA',
                'nik' => 'AP000000004',
                'password' => 'password123',
                'jabatan_id' => $jabatanDirut->id,
            ],
            [
                'role' => RoleUser::IT_STAFF,
                'email' => 'itstaff@example.com',
                'name' => 'STAFF IT',
                'nik' => 'AP000000005',
                'password' => 'password123',
                'jabatan_id' => $jabatanDefault->id,
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'nik' => $data['nik'],
                    'password' => Hash::make($data['password']),
                    'kantor_id' => $kantorPusat->id,
                    'jabatan_id' => $data['jabatan_id'],
                    'is_active' => true,
                    'email_verified' => true,
                ]
            );

            $role = Role::where('name', $data['role']->value)->firstOrFail();

            $user->roles()->sync([$role->id]);
        }

        $this->command->table(
            ['Role', 'Email', 'Password'],
            collect($users)->map(fn($u) => [
                $u['role']->label(),
                $u['email'],
                $u['password'],
            ])
        );

        $this->command->info('✓ Initial user seeder berhasil dijalankan.');
    }
}
