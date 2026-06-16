<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.submissions.index') }}" class="p-2 rounded-xl hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Pengajuan #{{ $submission->id }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">Pengajuan dari {{ $submission->user->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    {{-- Status Banner --}}
                    @php
                        $banner = [
                            'pending' => ['bg-amber-50 border-amber-200', 'text-amber-700', 'bg-amber-100', 'Pending - Menunggu diproses'],
                            'approved' => ['bg-emerald-50 border-emerald-200', 'text-emerald-700', 'bg-emerald-100', 'Disetujui'],
                            'rejected' => ['bg-red-50 border-red-200', 'text-red-700', 'bg-red-100', 'Ditolak'],
                        ];
                        $b = $banner[$submission->status];
                    @endphp
                    <div class="{{ $b[0] }} border rounded-xl p-4 mb-6 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full {{ $b[2] }}"></span>
                        <span class="text-sm font-medium {{ $b[1] }}">{{ $b[3] }}</span>
                    </div>

                    {{-- Info Pemohon --}}
                    <div class="bg-gray-50/50 rounded-xl p-5 mb-6">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Info Pemohon</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-sm font-bold">{{ substr($submission->user->name, 0, 1) }}</div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $submission->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $submission->user->email }}</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                <span class="text-gray-400">Terdaftar sejak:</span>
                                <p class="font-medium text-gray-700">{{ $submission->user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Surat --}}
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

                    {{-- Approve/Reject Actions --}}
                    @if($submission->status == 'pending')
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h4 class="font-semibold text-gray-800 mb-4">Proses Pengajuan</h4>
                            <div class="flex flex-wrap gap-3">
                                <button onclick="toggleApproveForm()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Setujui & Input Nomor
                                </button>
                                <button onclick="toggleRejectForm()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tolak
                                </button>
                            </div>

                            {{-- Approve Form --}}
                            <div id="approveForm" class="hidden mt-4 bg-emerald-50 border border-emerald-200 rounded-xl p-5">
                                <form action="{{ route('admin.submissions.approve', $submission) }}" method="POST" onsubmit="return confirm('Setujui pengajuan ini?')">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-emerald-800 mb-2">Nomor Surat</label>
                                        <input type="text" name="letter_number" class="w-full border-emerald-300 rounded-xl shadow-sm focus:border-emerald-500 focus:ring-emerald-500 bg-white px-4 py-2.5" placeholder="Contoh: 001/SKTM/DISDUKCAPIL/VI/2026" required>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm">Konfirmasi Setujui</button>
                                        <button type="button" onclick="toggleApproveForm()" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition">Batal</button>
                                    </div>
                                </form>
                            </div>

                            {{-- Reject Form --}}
                            <div id="rejectForm" class="hidden mt-4 bg-red-50 border border-red-200 rounded-xl p-5">
                                <form action="{{ route('admin.submissions.reject', $submission) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-red-800 mb-2">Alasan Penolakan</label>
                                        <textarea name="alasan_penolakan" rows="3" class="w-full border-red-300 rounded-xl shadow-sm focus:border-red-500 focus:ring-red-500 bg-white px-4 py-2.5" required></textarea>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition shadow-sm">Konfirmasi Tolak</button>
                                        <button type="button" onclick="toggleRejectForm()" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleApproveForm() {
            document.getElementById('approveForm').classList.toggle('hidden');
        }
        function toggleRejectForm() {
            document.getElementById('rejectForm').classList.toggle('hidden');
        }
    </script>
</x-app-layout>
