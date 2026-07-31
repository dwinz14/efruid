<?php

namespace Database\Seeders;

use App\Models\Kantor;
use Illuminate\Database\Seeder;

class KantorSeeder extends Seeder
{
    public function run(): void
    {
        $kantors = [
            // [nama, kode, is_pusat]
            // Kode fleksibel: bisa diganti angka atau kombinasi sesuai kebutuhan
            ['nama' => 'PUSAT',                 'kode' => '100', 'is_pusat' => true],
            ['nama' => 'KANTOR CABANG UTAMA',   'kode' => '101', 'is_pusat' => false],
            ['nama' => 'GURAH',                 'kode' => '102', 'is_pusat' => false],
            ['nama' => 'SAMBI',                 'kode' => '103', 'is_pusat' => false],
            ['nama' => 'JOMBANG',               'kode' => '105', 'is_pusat' => false],
            ['nama' => 'WATES',                 'kode' => '106', 'is_pusat' => false],
            ['nama' => 'BLITAR',                'kode' => '107', 'is_pusat' => false],
            ['nama' => 'TULUNGAGUNG',           'kode' => '108', 'is_pusat' => false],
            ['nama' => 'WARUJAYENG',            'kode' => '109', 'is_pusat' => false],
            ['nama' => 'NGANJUK',               'kode' => '110', 'is_pusat' => false],
            ['nama' => 'CARUBAN',               'kode' => '111', 'is_pusat' => false],
        ];

        foreach ($kantors as $data) {
            Kantor::updateOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
