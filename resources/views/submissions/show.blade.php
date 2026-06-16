<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Detail Pengajuan</h2>
            <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap pengajuan nomor surat</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    {{-- Status Banner --}}
                    @php
                        $banner = [
                            'pending' => ['bg-amber-50 border-amber-200', 'text-amber-700', 'bg-amber-100', 'Pending - Menunggu diproses admin'],
                            'approved' => ['bg-emerald-50 border-emerald-200', 'text-emerald-700', 'bg-emerald-100', 'Disetujui'],
                            'rejected' => ['bg-red-50 border-red-200', 'text-red-700', 'bg-red-100', 'Ditolak'],
                        ];
                        $b = $banner[$submission->status];
                    @endphp
                    <div class="{{ $b[0] }} border rounded-xl p-4 mb-6 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full {{ $b[2] }}"></span>
                        <span class="text-sm font-medium {{ $b[1] }}">{{ $b[3] }}</span>
                    </div>

                    {{-- Detail Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Jenis Surat</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $submission->letterType->name }}</p>
                            <p class="text-xs text-gray-400">{{ $submission->letterType->code }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal Pengajuan</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $submission->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Keperluan</p>
                            <p class="mt-1 text-gray-700">{{ $submission->keperluan }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">File Surat</p>
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="mt-1 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                Lihat / Download
                            </a>
                        </div>
                    </div>

                    {{-- Approved Info --}}
                    @if($submission->status == 'approved')
                        <div class="mt-6 bg-emerald-50 border border-emerald-200 rounded-xl p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="font-semibold text-emerald-700">Surat Disetujui</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-emerald-600">Nomor Surat:</span>
                                    <p class="font-bold text-emerald-800 mt-0.5">{{ $submission->letter_number }}</p>
                                </div>
                                <div>
                                    <span class="text-emerald-600">Disetujui oleh:</span>
                                    <p class="font-medium text-gray-700 mt-0.5">{{ $submission->approver?->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-emerald-600">Tanggal:</span>
                                    <p class="font-medium text-gray-700 mt-0.5">{{ $submission->approved_at?->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Rejected Info --}}
                    @if($submission->status == 'rejected')
                        <div class="mt-6 bg-red-50 border border-red-200 rounded-xl p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="font-semibold text-red-700">Surat Ditolak</span>
                            </div>
                            <div class="text-sm">
                                <span class="text-red-600">Alasan Penolakan:</span>
                                <p class="text-gray-700 mt-1 font-medium">{{ $submission->alasan_penolakan }}</p>
                                <p class="text-gray-500 mt-2">Tanggal: {{ $submission->rejected_at?->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="mt-6 flex flex-wrap items-center gap-3 pt-6 border-t border-gray-100">
                        @if($submission->status == 'pending' && $submission->user_id == auth()->id())
                            <form action="{{ route('submissions.destroy', $submission) }}" method="POST" onsubmit="return confirm('Hapus pengajuan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        @endif
                        @if($submission->status == 'rejected' && $submission->user_id == auth()->id())
                            <button onclick="toggleResubmitForm()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Ajukan Ulang
                            </button>
                        @endif
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.submissions.index') : route('submissions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali
                        </a>
                    </div>

                    {{-- Resubmit Form --}}
                    @if($submission->status == 'rejected' && $submission->user_id == auth()->id())
                        <div id="resubmitForm" class="hidden mt-6 pt-6 border-t border-gray-100">
                            <h4 class="font-semibold text-gray-800 mb-4">Form Pengajuan Ulang</h4>
                            <form action="{{ route('submissions.resubmit', $submission) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                                    <select name="letter_type_id" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                                        @foreach(\App\Models\LetterType::all() as $type)
                                            <option value="{{ $type->id }}" {{ $submission->letter_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                                    <textarea name="keperluan" rows="3" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>{{ $submission->keperluan }}</textarea>
                                </div>
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Surat Baru</label>
                                    <input type="file" name="file" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                                </div>
                                <div class="flex gap-3">
                                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Kirim Pengajuan Ulang
                                    </button>
                                    <button type="button" onclick="toggleResubmitForm()" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleResubmitForm() {
            const el = document.getElementById('resubmitForm');
            if (el) el.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
