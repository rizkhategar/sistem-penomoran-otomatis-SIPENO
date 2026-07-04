<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manual Penggunaan</h2>
            <p class="text-sm text-gray-500 mt-0.5">Panduan penggunaan aplikasi SIPENO</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8 space-y-8">

                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">1. Tentang SIPENO</h3>
                        <p class="text-gray-600 leading-relaxed">SIPENO (Sistem Informasi Pengajuan Nomor Surat) adalah aplikasi untuk mengelola pembuatan dan penomoran surat di lingkungan Disdukcapil. Setiap surat yang dibuat akan mendapatkan nomor secara otomatis.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">2. Login & Akun</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Buka aplikasi dan login menggunakan email dan password yang telah diberikan.</li>
                            <li>Setiap user memiliki <strong>Bidang</strong> masing-masing (misal: Umum, Perencanaan, dll).</li>
                            <li>User hanya bisa membuat surat dengan jenis surat yang sesuai bidangnya.</li>
                            <li>Admin dapat melihat semua data dan mengelola user, jenis surat.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">3. Membuat Surat Baru</h3>
                        <ol class="list-decimal list-inside text-gray-600 space-y-2">
                            <li>Klik menu <strong>"Buat Surat"</strong> di navigasi atas.</li>
                            <li>Pilih <strong>Jenis Surat</strong> yang sesuai.</li>
                            <li>Isi <strong>Keperluan</strong> surat.</li>
                            <li>Upload file pendukung jika ada (opsional).</li>
                            <li>Ceklis <strong>Surat Keputusan (SK)</strong> jika surat bersifat SK dan perlu tanggal mundur.</li>
                            <li>Klik <strong>"Buat Surat"</strong>. Nomor surat akan langsung digenerate otomatis.</li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">4. Nomor Surat Otomatis</h3>
                        <p class="text-gray-600 leading-relaxed">Format nomor surat: <code class="bg-gray-100 px-2 py-0.5 rounded text-sm">001/KODE/BIDANG/ROMAN/TAHUN</code></p>
                        <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                            <li>Nomor urut direset setiap bulan per bidang per jenis surat.</li>
                            <li>Setiap bidang memiliki kuota minimal 5 surat per bulan.</li>
                            <li>Minimal 5 surat dapat dibuat per hari.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">5. Surat Keputusan (SK) - Berlaku Mundur</h3>
                        <p class="text-gray-600 leading-relaxed">Untuk Surat Keputusan (SK), Anda dapat memilih tanggal surat mundur (retroactive). Nomor surat akan mengikuti bulan dan tahun dari tanggal yang dipilih, bukan tanggal pembuatan.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">6. Report / Laporan Bulanan</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Klik menu <strong>"Report"</strong> untuk melihat laporan bulanan.</li>
                            <li>Filter berdasarkan bulan, tahun, dan bidang.</li>
                            <li>Lihat total surat dan breakdown per jenis surat.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">7. Manajemen Data</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li><strong>Admin:</strong> Kelola user, jenis surat, lihat semua data.</li>
                            <li><strong>User:</strong> Lihat dan hapus surat milik sendiri.</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
