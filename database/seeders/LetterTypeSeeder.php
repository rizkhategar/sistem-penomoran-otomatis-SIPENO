<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Surat Keterangan Tidak Mampu', 'code' => '470-SKTM', 'bidang' => 'UMUM', 'description' => 'Surat keterangan untuk warga tidak mampu'],
            ['name' => 'Surat Keterangan Domisili', 'code' => '470-SKD', 'bidang' => 'UMUM', 'description' => 'Surat keterangan tempat tinggal/domisili'],
            ['name' => 'Surat Keterangan Usaha', 'code' => '500', 'bidang' => 'PERENCANAAN', 'description' => 'Surat keterangan untuk pengajuan izin usaha'],
            ['name' => 'Surat Keterangan Kematian', 'code' => '472', 'bidang' => 'UMUM', 'description' => 'Surat keterangan kematian warga'],
            ['name' => 'Surat Keterangan Kelahiran', 'code' => '474', 'bidang' => 'UMUM', 'description' => 'Surat keterangan kelahiran untuk akta'],
            ['name' => 'Surat Pengantar SKCK', 'code' => '331', 'bidang' => 'UMUM', 'description' => 'Surat pengantar pembuatan SKCK Kepolisian'],
            ['name' => 'Surat Keterangan Pindah', 'code' => '475', 'bidang' => 'UMUM', 'description' => 'Surat keterangan pindah domisili'],
            ['name' => 'Surat Keterangan Belum Menikah', 'code' => '474-SKBM', 'bidang' => 'UMUM', 'description' => 'Surat keterangan status belum menikah'],
            ['name' => 'Surat Sekretariatan', 'code' => '800', 'bidang' => 'SEKRETARIATAN', 'description' => 'Surat umum dari bidang sekretariatan'],
        ];

        foreach ($types as $type) {
            LetterType::firstOrCreate(
                ['name' => $type['name'], 'bidang' => $type['bidang']],
                $type + ['monthly_quota' => 5, 'daily_insertion' => 5, 'is_active' => true]
            );
        }
    }
}
