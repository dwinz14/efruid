<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatans = [
            // Level 1 — Direktur Utama
            ['nama' => 'DIREKTUR UTAMA',                    'urutan' => 1,  'level' => 1, 'is_lainnya' => false],

            // Level 2 — Direktur
            ['nama' => 'DIREKTUR BISNIS',                   'urutan' => 2,  'level' => 2, 'is_lainnya' => false],
            ['nama' => 'DIREKTUR KEPATUHAN',                'urutan' => 3,  'level' => 2, 'is_lainnya' => false],

            // Level 3 — Kepala Bagian / Pimpinan Cabang
            ['nama' => 'KEPALA BAGIAN BISNIS',              'urutan' => 4,  'level' => 3, 'is_lainnya' => false],
            ['nama' => 'KEPALA BAGIAN HRD',                 'urutan' => 5,  'level' => 3, 'is_lainnya' => false],
            ['nama' => 'KEPALA BAGIAN KEPATUHAN & MANRISK', 'urutan' => 6,  'level' => 3, 'is_lainnya' => false],
            ['nama' => 'KEPALA BAGIAN OPERASIONAL',         'urutan' => 7,  'level' => 3, 'is_lainnya' => false],
            ['nama' => 'KEPALA BAGIAN SKAI',                'urutan' => 8,  'level' => 3, 'is_lainnya' => false],
            ['nama' => 'PIMPINAN CABANG',                   'urutan' => 9,  'level' => 3, 'is_lainnya' => false],

            // Level 4 — Kasie / Kepala Unit
            ['nama' => 'KASIE COLLECTION',                  'urutan' => 10, 'level' => 4, 'is_lainnya' => false],
            ['nama' => 'KASIE CUSTOMER SERVICE',            'urutan' => 11, 'level' => 4, 'is_lainnya' => false],
            ['nama' => 'KASIE IT',                          'urutan' => 12, 'level' => 4, 'is_lainnya' => false],
            ['nama' => 'KASIE MARKETING',                   'urutan' => 13, 'level' => 4, 'is_lainnya' => false],
            ['nama' => 'KASIE OPERASIONAL',                 'urutan' => 14, 'level' => 4, 'is_lainnya' => false],
            ['nama' => 'KEPALA KANTOR KAS',                 'urutan' => 15, 'level' => 4, 'is_lainnya' => false],

            // Level 5 — Staff & Pelaksana
            ['nama' => 'ACCOUNT OFFICER STAFF',             'urutan' => 16, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'ACCOUNTING STAFF',                  'urutan' => 17, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'ADMIN KREDIT STAFF',                'urutan' => 18, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'CUSTOMER SERVICE',                  'urutan' => 19, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'HEAD TELLER',                       'urutan' => 20, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'KEPATUHAN & MANRISK STAFF',         'urutan' => 21, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'KOORDINATOR KREDIT',                'urutan' => 22, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'SEKRETARIS DIREKSI',                'urutan' => 23, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'STAFF IT',                          'urutan' => 24, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'STAFF SKAI',                        'urutan' => 25, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'TABUNGAN DEPOSITO',                 'urutan' => 26, 'level' => 5, 'is_lainnya' => false],
            ['nama' => 'TELLER',                            'urutan' => 27, 'level' => 5, 'is_lainnya' => false],

            // Level 5 — LAINNYA (selalu di akhir)
            ['nama' => 'LAINNYA',                           'urutan' => 99, 'level' => 5, 'is_lainnya' => true],
        ];

        foreach ($jabatans as $data) {
            Jabatan::updateOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }
    }
}
