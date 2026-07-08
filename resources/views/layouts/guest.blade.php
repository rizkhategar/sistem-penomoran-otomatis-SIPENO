<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIPENO - {{ $title ?? 'Disdukcapil' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        {{-- Left Side --}}
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-800 to-blue-900 relative overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-400/10 rounded-full blur-3xl"></div>
            </div>
            <div class="relative z-10 flex flex-col justify-center px-16">
                <a href="/" class="flex items-center gap-3 mb-12">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                    <div>
                        <span class="text-white font-bold text-lg">SIPENO</span>
                        <span class="text-blue-200 text-xs block -mt-1">Disdukcapil</span>
                    </div>
                </a>
                <h1 class="text-4xl font-bold text-white leading-tight">Sistem Pengajuan<br>Nomor Surat</h1>
                <p class="mt-4 text-blue-200 text-lg max-w-md leading-relaxed">Ajukan surat online, dapatkan nomor resmi dengan cepat dan transparan.</p>
                <div class="mt-10 flex items-center gap-4 text-blue-200 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Mudah
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Cepat
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Aman
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side (Form) --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-md">
                <div class="lg:hidden flex justify-center mb-8">
                    <a href="/" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                        <div>
                            <span class="text-gray-800 font-bold text-lg">SIPENO</span>
                            <span class="text-gray-400 text-xs block -mt-1">Disdukcapil</span>
                        </div>
                    </a>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
