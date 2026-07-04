<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Semua Surat</h2>
            <p class="text-sm text-gray-500 mt-0.5">Daftar seluruh surat yang telah dibuat</p>
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-50">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembuat</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bidang</th>
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
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $sub->letterType->name }}</td>
                                <td class="px-6 py-4"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $sub->letterType->bidang ?? '-' }}</span></td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $sub->letter_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.submissions.show', $sub) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        Detail
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
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
