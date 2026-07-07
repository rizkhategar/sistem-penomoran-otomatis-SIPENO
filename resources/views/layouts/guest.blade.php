<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIPENO') }} - {{ $title ?? 'Disdukcapil' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen text-slate-700 overflow-x-hidden">
    <div class="min-h-screen flex relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-blue-300/30 blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-cyan-300/25 blur-3xl"></div>
        </div>

        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-slate-950">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(59,130,246,.34),transparent_32rem),radial-gradient(circle_at_80%_70%,rgba(6,182,212,.26),transparent_28rem)]"></div>
            <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px); background-size: 42px 42px;"></div>
            <div class="relative z-10 flex flex-col justify-between px-16 py-14 text-white">
                <a href="/" class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/12 ring-1 ring-white/15 shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                    </span>
                    <div>
                        <span class="font-extrabold text-xl tracking-tight">SIPENO</span>
                        <span class="text-cyan-200/80 text-xs block -mt-0.5">Sistem Penomoran Surat</span>
                    </div>
                </a>

                <div class="max-w-lg">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-medium text-cyan-100 shadow-sm backdrop-blur-xl">
                        <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                        Pelayanan surat lebih tertib dan transparan
                    </div>
                    <h1 class="mt-7 text-5xl font-extrabold leading-tight tracking-tight text-balance">Sistem Pengajuan Nomor Surat Modern</h1>
                    <p class="mt-5 text-lg leading-relaxed text-slate-300">Kelola nomor surat, bidang, jenis surat, arsip pengajuan, dan laporan bulanan dalam satu dashboard yang rapi.</p>
                    <div class="mt-10 grid grid-cols-3 gap-3 text-sm">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-xl">
                            <p class="font-bold text-white">Urut</p>
                            <p class="mt-1 text-xs text-slate-300">Nomor global</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-xl">
                            <p class="font-bold text-white">Cepat</p>
                            <p class="mt-1 text-xs text-slate-300">Input ringkas</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-xl">
                            <p class="font-bold text-white">PDF</p>
                            <p class="mt-1 text-xs text-slate-300">Laporan cetak</p>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-slate-400">© {{ date('Y') }} SIPENO Disdukcapil</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-md">
                <div class="lg:hidden flex justify-center mb-8">
                    <a href="/" class="flex items-center gap-3 rounded-3xl border border-white/70 bg-white/80 px-5 py-3 shadow-xl shadow-slate-200/60 backdrop-blur-xl">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                        <div>
                            <span class="text-slate-900 font-extrabold text-lg">SIPENO</span>
                            <span class="text-slate-400 text-xs block -mt-1">Disdukcapil</span>
                        </div>
                    </a>
                </div>
                <div class="sipeno-card p-8 sm:p-9">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
