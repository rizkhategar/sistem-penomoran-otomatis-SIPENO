<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Surat per Bidang</h2>
            <p class="text-sm text-gray-500 mt-0.5">Lihat dan atur jenis surat yang tersedia pada setiap bidang aktif.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="mb-6 bg-blue-50 border border-blue-100 rounded-2xl p-5 text-sm text-blue-900">
                <p class="font-semibold mb-2">Fungsi halaman ini</p>
                <p>
                    Setiap jenis surat aktif otomatis tersedia pada seluruh <b>{{ $activeBidangCount }} bidang aktif</b>.
                    Halaman ini dipakai untuk melihat pasangan tersebut dan mengatur <b>kuota bulanan</b>,
                    <b>batas sisipan per hari</b>, atau menonaktifkan jenis surat pada bidang tertentu melalui tombol Edit.
                    Admin tidak perlu memasangkan surat secara manual.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($letterTypes as $type)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-mono font-bold">{{ $type->code }}</span>
                            <h3 class="font-semibold text-gray-800 mt-2">{{ $type->masterJenisSurat->name ?? $type->name }}</h3>
                        </div>
                        <a href="{{ route('admin.letter-types.edit', $type) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-blue-600 transition" title="Atur kuota, sisipan, dan status">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                    @if($type->description)
                        <p class="text-sm text-gray-500">{{ $type->description }}</p>
                    @endif
                    <div class="mt-3 flex items-center gap-2 text-xs text-gray-400 flex-wrap">
                        <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $type->masterBidang->name }}</span>
                        <span>{{ $type->submissions_count }} surat</span>
                        <span>Kuota {{ $type->monthly_quota ?? 5 }}/bulan</span>
                        <span>Sisipan {{ $type->daily_insertion ?? 5 }}/hari</span>
                        @if(!$type->is_active)
                            <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded-full">Nonaktif</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="sm:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-2xl border border-gray-100">
                    <svg class="w-14 h-14 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-gray-500 font-medium mb-1">Belum ada pengaturan surat per bidang</p>
                    <p class="text-gray-400 text-sm">Tambahkan Data Bidang dan Data Jenis Surat. Sistem akan membuat pasangannya secara otomatis.</p>
                </div>
                @endforelse
            </div>

            @if($letterTypes->hasPages())
                <div class="mt-6">{{ $letterTypes->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
