<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Manual Penggunaan SIPENO</h2>
                <p class="text-sm text-gray-500 mt-0.5">Panduan singkat penggunaan Sistem Pengajuan Nomor Surat</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 w-fit">
                Panduan Admin & User
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gradient-to-r from-blue-800 to-blue-900 rounded-3xl shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="max-w-3xl">
                            <p class="text-blue-200 text-sm font-medium mb-2">SIPENO DISDUKCAPIL</p>
                            <h1 class="text-2xl sm:text-3xl font-bold leading-tight">Panduan penggunaan aplikasi penomoran surat</h1>
                            <p class="mt-3 text-blue-100 leading-relaxed">Gunakan halaman ini sebagai acuan untuk login, membuat surat, mengatur data bidang, mengelola jenis surat, melihat laporan, dan mengunduh PDF.</p>
                        </div>
                        <div class="bg-white/10 rounded-2xl p-4 border border-white/10 min-w-[220px]">
                            <p class="text-xs text-blue-200 mb-2">Alur utama</p>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-white text-blue-800 flex items-center justify-center text-xs font-bold">1</span> Login</div>
                                <div class="flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-white text-blue-800 flex items-center justify-center text-xs font-bold">2</span> Buat Surat</div>
                                <div class="flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-white text-blue-800 flex items-center justify-center text-xs font-bold">3</span> Nomor Otomatis</div>
                                <div class="flex items-center gap-2"><span class="w-6 h-6 rounded-full bg-white text-blue-800 flex items-center justify-center text-xs font-bold">4</span> Report PDF</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="#panduan-user" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Panduan User</h3>
                    <p class="text-sm text-gray-500 mt-1">Cara membuat surat dan melihat data surat.</p>
                </a>
                <a href="#panduan-admin" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Panduan Admin</h3>
                    <p class="text-sm text-gray-500 mt-1">Cara mengatur data bidang, jenis surat, dan user.</p>
                </a>
                <a href="#report" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-8 0h8m-8 0H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4" /></svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Report Bulanan</h3>
                    <p class="text-sm text-gray-500 mt-1">Cara melihat laporan dan download PDF.</p>
                </a>
            </div>

            <div id="panduan-user" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">A. Panduan User</h3>
                    <p class="text-sm text-gray-500 mt-1">Bagian ini digunakan oleh pegawai/user yang membuat pengajuan nomor surat.</p>
                </div>
                <div class="p-6 sm:p-8 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                        <div class="rounded-2xl border border-gray-100 p-5">
                            <h4 class="font-semibold text-gray-800 mb-3">1. Login ke Aplikasi</h4>
                            <ol class="space-y-3 text-sm text-gray-600">
                                <li class="flex gap-3"><span class="font-bold text-blue-600">1.</span><span>Buka alamat aplikasi SIPENO di browser.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">2.</span><span>Masukkan email dan password akun.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">3.</span><span>Klik tombol <b>Masuk</b>.</span></li>
                            </ol>
                            <div class="mt-4 text-xs bg-yellow-50 text-yellow-800 border border-yellow-100 rounded-xl p-3">Jika lupa password, hubungi admin agar akun dibantu diperbarui.</div>
                        </div>

                        <div class="rounded-2xl border border-gray-100 p-5">
                            <h4 class="font-semibold text-gray-800 mb-3">2. Membuat Surat Baru</h4>
                            <ol class="space-y-3 text-sm text-gray-600">
                                <li class="flex gap-3"><span class="font-bold text-blue-600">1.</span><span>Klik menu <b>Buat Surat</b>.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">2.</span><span>Pilih <b>Bidang</b> dan <b>Jenis Surat</b>.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">3.</span><span>Isi <b>Format Nomor Surat</b> sesuai kebutuhan instansi.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">4.</span><span>Isi <b>Pengolah</b>, <b>Ditujukan Kepada</b>, dan <b>Keperluan</b>.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">5.</span><span>Upload file pendukung jika ada, lalu klik <b>Buat Surat</b>.</span></li>
                            </ol>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 p-5">
                        <h4 class="font-semibold text-gray-800 mb-3">3. Memahami Nomor Surat</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                <p class="font-semibold text-blue-900">Nomor urut otomatis</p>
                                <p class="text-blue-800 mt-1">Sistem menambahkan nomor urut di bagian depan nomor surat.</p>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4">
                                <p class="font-semibold text-gray-800">Format diisi manual</p>
                                <p class="text-gray-600 mt-1">User mengetik format setelah nomor urut sesuai aturan kantor.</p>
                            </div>
                            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4">
                                <p class="font-semibold text-emerald-900">Nomor berurutan</p>
                                <p class="text-emerald-800 mt-1">Nomor surat berjalan otomatis dan tidak perlu diketik manual.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 p-5">
                        <h4 class="font-semibold text-gray-800 mb-3">4. Menggunakan Tanggal Mundur / Sisipan Nomor</h4>
                        <p class="text-sm text-gray-600 leading-relaxed">Gunakan pilihan <b>tanggal mundur / sisipan nomor</b> jika surat dibuat hari ini tetapi perlu memakai tanggal sebelumnya. Sistem membatasi sisipan maksimal sesuai pengaturan, umumnya <b>5 nomor per tanggal</b>.</p>
                        <div class="mt-4 bg-blue-50 border border-blue-100 rounded-2xl p-4 text-sm text-blue-900">
                            Contoh: jika nomor reguler hari ini berhenti di <b>025</b> dan tersedia 5 sisipan, maka slot <b>026–030</b> disediakan untuk sisipan. Nomor reguler hari berikutnya mulai dari <b>031</b>.
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 p-5">
                        <h4 class="font-semibold text-gray-800 mb-3">5. Melihat Daftar Surat</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li>• Klik menu <b>Semua Surat</b> untuk melihat daftar surat yang sudah dibuat.</li>
                            <li>• User dapat melihat informasi nomor surat, bidang, jenis surat, tujuan, pengolah, dan tanggal.</li>
                            <li>• Gunakan detail surat untuk mengecek data yang sudah disimpan.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="panduan-admin" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">B. Panduan Admin</h3>
                    <p class="text-sm text-gray-500 mt-1">Bagian ini digunakan oleh admin untuk mengatur data dasar dan pengguna.</p>
                </div>
                <div class="p-6 sm:p-8 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                        <div class="rounded-2xl border border-gray-100 p-5">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold mb-3">1</div>
                            <h4 class="font-semibold text-gray-800 mb-2">Data Bidang</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">Menu ini dipakai untuk menambah, mengedit, menghapus, atau menonaktifkan bidang. Bidang baru otomatis mendapat semua jenis surat aktif.</p>
                        </div>
                        <div class="rounded-2xl border border-gray-100 p-5">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold mb-3">2</div>
                            <h4 class="font-semibold text-gray-800 mb-2">Data Jenis Surat</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">Menu ini dipakai untuk menambah jenis surat. Jenis surat baru otomatis tersedia untuk semua bidang aktif.</p>
                        </div>
                        <div class="rounded-2xl border border-gray-100 p-5">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold mb-3">3</div>
                            <h4 class="font-semibold text-gray-800 mb-2">Surat per Bidang</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">Menu ini dipakai untuk mengecek jenis surat yang tersedia pada setiap bidang, mengubah kuota, sisipan per hari, dan status aktif.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 p-5">
                        <h4 class="font-semibold text-gray-800 mb-3">Alur Mengatur Jenis Surat</h4>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex gap-3"><span class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0">1</span><span>Buka <b>Data Bidang</b> untuk memastikan semua bidang sudah ada dan aktif.</span></div>
                            <div class="flex gap-3"><span class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0">2</span><span>Buka <b>Data Jenis Surat</b>, lalu klik <b>Tambah Jenis Surat</b> jika ada jenis baru.</span></div>
                            <div class="flex gap-3"><span class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0">3</span><span>Sistem otomatis memasangkan jenis surat baru ke seluruh bidang aktif.</span></div>
                            <div class="flex gap-3"><span class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0">4</span><span>Buka <b>Surat per Bidang</b> jika ingin mengecek atau menonaktifkan jenis surat tertentu pada bidang tertentu.</span></div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 p-5">
                        <h4 class="font-semibold text-gray-800 mb-3">Mengelola User</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li>• Klik menu <b>User</b> untuk melihat daftar akun.</li>
                            <li>• Klik <b>Tambah User</b> untuk membuat akun baru.</li>
                            <li>• Pilih role <b>User</b> atau <b>Admin</b>.</li>
                            <li>• Pilih bidang sesuai unit kerja pegawai.</li>
                            <li>• Gunakan menu edit untuk mengubah nama, email, role, bidang, atau password.</li>
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-red-100 bg-red-50 p-5">
                        <h4 class="font-semibold text-red-800 mb-2">Catatan Penting Admin</h4>
                        <p class="text-sm text-red-700 leading-relaxed">Jangan menghapus data bidang atau jenis surat yang sudah dipakai. Jika sudah tidak digunakan, lebih aman ubah status menjadi <b>Nonaktif</b> agar riwayat surat lama tetap aman.</p>
                    </div>
                </div>
            </div>

            <div id="report" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">C. Report dan Download PDF</h3>
                    <p class="text-sm text-gray-500 mt-1">Gunakan menu Report untuk melihat rekap surat bulanan dan mengunduh PDF.</p>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="rounded-2xl border border-gray-100 p-5">
                            <h4 class="font-semibold text-gray-800 mb-3">Melihat Report</h4>
                            <ol class="space-y-3 text-sm text-gray-600">
                                <li class="flex gap-3"><span class="font-bold text-blue-600">1.</span><span>Klik menu <b>Report</b>.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">2.</span><span>Pilih bulan dan tahun laporan.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">3.</span><span>Pilih bidang tertentu atau pilih semua bidang.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">4.</span><span>Klik <b>Tampilkan</b>.</span></li>
                            </ol>
                        </div>
                        <div class="rounded-2xl border border-gray-100 p-5">
                            <h4 class="font-semibold text-gray-800 mb-3">Download PDF</h4>
                            <ol class="space-y-3 text-sm text-gray-600">
                                <li class="flex gap-3"><span class="font-bold text-blue-600">1.</span><span>Atur filter bulan, tahun, dan bidang.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">2.</span><span>Klik tombol <b>Download PDF</b>.</span></li>
                                <li class="flex gap-3"><span class="font-bold text-blue-600">3.</span><span>File PDF akan terunduh dengan kop SIPENO Disdukcapil dan tabel laporan.</span></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">D. Ringkasan Menu</h3>
                    <p class="text-sm text-gray-500 mt-1">Fungsi setiap menu pada navigasi SIPENO.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Menu</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Digunakan Oleh</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Fungsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr><td class="px-6 py-4 font-semibold text-gray-800">Dashboard</td><td class="px-6 py-4 text-gray-600">Admin & User</td><td class="px-6 py-4 text-gray-600">Melihat ringkasan data surat.</td></tr>
                            <tr><td class="px-6 py-4 font-semibold text-gray-800">Buat Surat</td><td class="px-6 py-4 text-gray-600">User</td><td class="px-6 py-4 text-gray-600">Membuat surat dan mendapatkan nomor otomatis.</td></tr>
                            <tr><td class="px-6 py-4 font-semibold text-gray-800">Semua Surat</td><td class="px-6 py-4 text-gray-600">User</td><td class="px-6 py-4 text-gray-600">Melihat daftar surat yang sudah dibuat.</td></tr>
                            <tr><td class="px-6 py-4 font-semibold text-gray-800">Kelola Surat</td><td class="px-6 py-4 text-gray-600">Admin</td><td class="px-6 py-4 text-gray-600">Melihat dan mengelola data surat yang masuk.</td></tr>
                            <tr><td class="px-6 py-4 font-semibold text-gray-800">Data Bidang</td><td class="px-6 py-4 text-gray-600">Admin</td><td class="px-6 py-4 text-gray-600">Menambah dan mengatur daftar bidang.</td></tr>
                            <tr><td class="px-6 py-4 font-semibold text-gray-800">Data Jenis Surat</td><td class="px-6 py-4 text-gray-600">Admin</td><td class="px-6 py-4 text-gray-600">Menambah dan mengatur daftar jenis surat.</td></tr>
                            <tr><td class="px-6 py-4 font-semibold text-gray-800">Surat per Bidang</td><td class="px-6 py-4 text-gray-600">Admin</td><td class="px-6 py-4 text-gray-600">Mengatur jenis surat yang tersedia pada setiap bidang.</td></tr>
                            <tr><td class="px-6 py-4 font-semibold text-gray-800">User</td><td class="px-6 py-4 text-gray-600">Admin</td><td class="px-6 py-4 text-gray-600">Membuat dan mengubah akun pengguna.</td></tr>
                            <tr><td class="px-6 py-4 font-semibold text-gray-800">Report</td><td class="px-6 py-4 text-gray-600">Admin & User</td><td class="px-6 py-4 text-gray-600">Melihat laporan bulanan dan mengunduh PDF.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
