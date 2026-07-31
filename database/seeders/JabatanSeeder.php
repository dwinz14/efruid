<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        // Port dari jabatan.js legacy v1, urutkan alfabetis kecuali LAINNYA di akhir
        $jabatans = [
            ['nama' => 'ACCOUNT OFFICER STAFF',          'urutan' => 1,  'is_lainnya' => false],
            ['nama' => 'ACCOUNTING STAFF',               'urutan' => 2,  'is_lainnya' => false],
            ['nama' => 'ADMIN KREDIT STAFF',             'urutan' => 3,  'is_lainnya' => false],
            ['nama' => 'CUSTOMER SERVICE',               'urutan' => 4,  'is_lainnya' => false],
            ['nama' => 'DIREKTUR BISNIS',                'urutan' => 5,  'is_lainnya' => false],
            ['nama' => 'DIREKTUR KEPATUHAN',             'urutan' => 6,  'is_lainnya' => false],
            ['nama' => 'DIREKTUR UTAMA',                 'urutan' => 7,  'is_lainnya' => false],
            ['nama' => 'HEAD TELLER',                    'urutan' => 8,  'is_lainnya' => false],
            ['nama' => 'KASIE COLLECTION',               'urutan' => 9,  'is_lainnya' => false],
            ['nama' => 'KASIE CUSTOMER SERVICE',         'urutan' => 10, 'is_lainnya' => false],
            ['nama' => 'KASIE IT',                       'urutan' => 11, 'is_lainnya' => false],
            ['nama' => 'KASIE MARKETING',                'urutan' => 12, 'is_lainnya' => false],
            ['nama' => 'KASIE OPERASIONAL',              'urutan' => 13, 'is_lainnya' => false],
            ['nama' => 'KEPALA BAGIAN BISNIS',           'urutan' => 14, 'is_lainnya' => false],
            ['nama' => 'KEPALA BAGIAN HRD',              'urutan' => 15, 'is_lainnya' => false],
            ['nama' => 'KEPALA BAGIAN KEPATUHAN & MANRISK', 'urutan' => 16, 'is_lainnya' => false],
            ['nama' => 'KEPALA BAGIAN OPERASIONAL',      'urutan' => 17, 'is_lainnya' => false],
            ['nama' => 'KEPALA BAGIAN SKAI',             'urutan' => 18, 'is_lainnya' => false],
            ['nama' => 'KEPALA KANTOR KAS',              'urutan' => 19, 'is_lainnya' => false],
            ['nama' => 'KEPATUHAN & MANRISK STAFF',      'urutan' => 20, 'is_lainnya' => false],
            ['nama' => 'KOORDINATOR KREDIT',             'urutan' => 21, 'is_lainnya' => false],
            ['nama' => 'PIMPINAN CABANG',                'urutan' => 22, 'is_lainnya' => false],
            ['nama' => 'SEKRETARIS DIREKSI',             'urutan' => 23, 'is_lainnya' => false],
            ['nama' => 'STAFF IT',                       'urutan' => 24, 'is_lainnya' => false],
            ['nama' => 'STAFF SKAI',                     'urutan' => 25, 'is_lainnya' => false],
            ['nama' => 'TABUNGAN DEPOSITO',              'urutan' => 26, 'is_lainnya' => false],
            ['nama' => 'TELLER',                         'urutan' => 27, 'is_lainnya' => false],
            ['nama' => 'LAINNYA',                        'urutan' => 99, 'is_lainnya' => true],
        ];

        foreach ($jabatans as $data) {
            Jabatan::updateOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
