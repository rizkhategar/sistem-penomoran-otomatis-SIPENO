<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Surat Keterangan Tidak Mampu', 'code' => 'SKTM', 'description' => 'Surat keterangan untuk warga tidak mampu'],
            ['name' => 'Surat Keterangan Domisili', 'code' => 'SKD', 'description' => 'Surat keterangan tempat tinggal/domisili'],
            ['name' => 'Surat Keterangan Usaha', 'code' => 'SKU', 'description' => 'Surat keterangan untuk pengajuan izin usaha'],
            ['name' => 'Surat Keterangan Kematian', 'code' => 'SKK', 'description' => 'Surat keterangan kematian warga'],
            ['name' => 'Surat Keterangan Kelahiran', 'code' => 'SKL', 'description' => 'Surat keterangan kelahiran untuk akta'],
            ['name' => 'Surat Pengantar SKCK', 'code' => 'SP-SKCK', 'description' => 'Surat pengantar pembuatan SKCK Kepolisian'],
            ['name' => 'Surat Keterangan Pindah', 'code' => 'SKP', 'description' => 'Surat keterangan pindah domisili'],
            ['name' => 'Surat Keterangan Belum Menikah', 'code' => 'SKBM', 'description' => 'Surat keterangan status belum menikah'],
        ];

        foreach ($types as $type) {
            LetterType::create($type);
        }
    }
}
