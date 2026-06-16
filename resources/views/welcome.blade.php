<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPENO - Sistem Pengajuan Nomor Surat Disdukcapil</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    {{-- Navbar --}}
    <nav class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                    <div>
                        <span class="text-gray-800 font-bold text-sm">SIPENO</span>
                        <span class="text-gray-400 text-xs block -mt-0.5">Disdukcapil</span>
                    </div>
                </a>
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-800 transition">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="pt-32 pb-20 lg:pb-28 bg-gradient-to-br from-blue-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        Dinas Kependudukan & Pencatatan Sipil
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight">
                        Ajukan Nomor Surat
                        <span class="text-blue-600">Secara Online</span>
                    </h1>
                    <p class="mt-4 text-lg text-gray-500 max-w-xl leading-relaxed">
                        Sistem pengajuan nomor surat online untuk warga. Ajukan surat Anda, kami yang memproses nomornya. Mudah, cepat, dan transparan.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4 justify-center lg:justify-start">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition shadow-lg shadow-blue-200">Dashboard</a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                    Daftar Sekarang
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition shadow-sm">Masuk</a>
                            @endauth
                        @endif
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="absolute -top-6 -right-6 w-72 h-72 bg-blue-100 rounded-full blur-3xl opacity-60"></div>
                        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-700 font-bold text-xs">D</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">SIPENO Disdukcapil</p>
                                    <p class="text-sm font-medium text-gray-800">Pengajuan Surat</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-xl border border-amber-100">
                                    <span class="w-2 h-2 bg-amber-400 rounded-full"></span>
                                    <span class="text-sm text-gray-600">1 pengajuan <span class="text-amber-600 font-medium">menunggu</span></span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                                    <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                                    <span class="text-sm text-gray-600">5 surat <span class="text-emerald-600 font-medium">disetujui</span></span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                    <span class="text-sm text-gray-600">Proses cepat, transparan</span>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">Status pengajuan</span>
                                    <span class="text-blue-600 font-medium">Lihat detail &rarr;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Fitur --}}
    <section id="fitur" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-sm font-medium mb-4">Fitur</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Kenapa Menggunakan SIPENO?</h2>
                <p class="mt-3 text-gray-500 max-w-2xl mx-auto">Kemudahan pengajuan nomor surat secara online tanpa harus datang ke kantor.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Ajukan Online</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Cukup upload file surat dan isi keperluan dari rumah. Tidak perlu antri di kantor.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Proses Cepat</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Admin akan memproses pengajuan Anda dan memberikan nomor surat resmi.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Pantau Status</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Lihat status pengajuan kapan saja. Jika ditolak, Anda tahu alasannya dan bisa ajukan ulang.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Banyak Jenis Surat</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Tersedia berbagai jenis surat: SKTM, SKD, SKU, SKCK, dan lainnya.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Data Aman</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Data Anda tersimpan dengan aman dan hanya diproses oleh petugas yang berwenang.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Ajukan Ulang</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Jika ditolak, bisa langsung ajukan ulang dengan perbaikan tanpa harus daftar ulang.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Cara Kerja --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-sm font-medium mb-4">Alur</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Cara Kerja</h2>
                <p class="mt-3 text-gray-500 max-w-2xl mx-auto">Hanya 3 langkah mudah untuk mendapatkan nomor surat resmi.</p>
            </div>
            <div class="grid sm:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Ajukan Surat</h3>
                    <p class="text-sm text-gray-500">Upload file surat Anda dan isi keperluan. Pilih jenis surat yang sesuai.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <span class="text-2xl font-bold text-blue-600">2</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Admin Proses</h3>
                    <p class="text-sm text-gray-500">Admin Disdukcapil akan memeriksa dan memproses pengajuan Anda.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <span class="text-2xl font-bold text-blue-600">3</span>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Dapatkan Nomor</h3>
                    <p class="text-sm text-gray-500">Surat Anda mendapat nomor resmi. Anda bisa download dan gunakan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 rounded-3xl p-12 shadow-xl">
                <h2 class="text-3xl font-bold text-white mb-3">Siap Mengajukan Surat?</h2>
                <p class="text-blue-100 mb-8 max-w-lg mx-auto">Daftar sekarang dan ajukan surat Anda secara online. Mudah, cepat, tanpa ribet.</p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl text-sm font-semibold text-blue-700 bg-white hover:bg-blue-50 transition shadow-lg">
                        Daftar Akun Gratis
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-sm">D</span>
                        </div>
                        <div>
                            <span class="text-white font-semibold text-sm">SIPENO</span>
                            <span class="text-gray-500 text-xs block -mt-0.5">Disdukcapil</span>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed">Sistem Informasi Pengajuan Nomor Surat Dinas Kependudukan dan Pencatatan Sipil.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Kantor Disdukcapil</li>
                        <li>Jl. Contoh No. 123, Kota</li>
                        <li>(021) 1234-5678</li>
                        <li>disdukcapil@kota.go.id</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Jam Operasional</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Senin - Kamis: 08.00 - 15.00</li>
                        <li>Jumat: 08.00 - 14.00</li>
                        <li>Sabtu - Minggu: Libur</li>
                    </ul>
                </div>
            </div>
            <div class="mt-10 pt-8 border-t border-gray-800 text-center text-sm">
                &copy; {{ date('Y') }} SIPENO Disdukcapil. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
