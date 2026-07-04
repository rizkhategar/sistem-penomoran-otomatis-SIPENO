<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.submissions.index') }}" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Surat</h2>
                <p class="text-sm text-gray-500 mt-0.5">Dibuat oleh {{ $submission->user->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    @if($submission->is_sk)
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-sm font-medium text-amber-700">Surat Keputusan (SK) - Berlaku mundur</span>
                    </div>
                    @endif

                    <div class="bg-gray-50/50 rounded-xl p-5 mb-6">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Info Pembuat</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-sm font-bold">{{ substr($submission->user->name, 0, 1) }}</div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $submission->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $submission->user->email }}</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                <span class="text-gray-400">Bidang:</span>
                                <p class="font-medium text-gray-700">{{ $submission->letterType->bidang ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Jenis Surat</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $submission->letterType->name }}</p>
                            <p class="text-xs text-gray-400">{{ $submission->letterType->code }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $submission->submission_date?->format('d/m/Y') ?? $submission->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Nomor Surat</p>
                            <p class="mt-1 text-lg font-bold text-blue-700">{{ $submission->letter_number }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Keperluan</p>
                            <p class="mt-1 text-gray-700">{{ $submission->keperluan }}</p>
                        </div>
                        @if($submission->file_path)
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">File</p>
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="mt-1 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                Lihat / Download
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.submissions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
