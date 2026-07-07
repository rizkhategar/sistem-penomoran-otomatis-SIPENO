<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detail Surat</h2>
            <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap surat</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    @if($submission->is_sk)
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-sm font-medium text-amber-700">Menggunakan tanggal mundur / sisipan nomor</span>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pembuat</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $submission->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $submission->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Bidang</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $submission->letterType->bidang ?? '-' }}</p>
                        </div>
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
                            @if($submission->number_format)
                                <p class="text-xs text-gray-400 mt-1">Format input: {{ $submission->number_format }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pengolah</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $submission->pengolah ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Ditujukan Kepada</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $submission->ditujukan_kepada ?? '-' }}</p>
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

                    <div class="mt-6 flex flex-wrap items-center gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('submissions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali
                        </a>
                        @if(auth()->user()->isAdmin() || $submission->user_id == auth()->id())
                        <form action="{{ route('submissions.destroy', $submission) }}" method="POST" onsubmit="return confirm('Hapus surat ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
