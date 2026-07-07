<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Semua Surat</h2>
                <p class="text-sm text-gray-500 mt-0.5">Daftar semua surat yang telah diajukan oleh seluruh user</p>
            </div>
            @if(!auth()->user()->isAdmin())
            <a href="{{ route('submissions.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-sm text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Baru
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-50">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembuat</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengolah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ditujukan Kepada</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($submissions as $index => $sub)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $submissions->firstItem() + $index }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xs font-bold">{{ substr($sub->user->name, 0, 1) }}</div>
                                        <span class="font-medium text-gray-800 text-sm">{{ $sub->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-800">{{ $sub->letterType->name }}</span>
                                    <span class="text-xs text-gray-400 block">{{ $sub->letterType->code }} - {{ $sub->letterType->bidang ?? 'Umum' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $sub->pengolah ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $sub->ditujukan_kepada ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $sub->letter_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ ($sub->submission_date ?: $sub->created_at)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center gap-2 justify-end">
                                        <a href="{{ route('submissions.show', $sub) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Detail</a>
                                        @if(auth()->user()->isAdmin() || $sub->user_id == auth()->id())
                                        <form action="{{ route('submissions.destroy', $sub) }}" method="POST" onsubmit="return confirm('Hapus surat ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600 font-medium text-sm">Hapus</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <svg class="w-14 h-14 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-gray-400 font-medium">Belum ada surat</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($submissions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">{{ $submissions->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
