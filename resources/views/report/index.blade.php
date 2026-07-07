<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Report Bulanan</h2>
            <p class="text-sm text-gray-500 mt-0.5">Laporan surat per bulan yang bisa diunduh sebagai PDF</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <form method="GET" action="{{ route('report.index') }}" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
                        <select name="bulan" class="border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2">
                            @foreach($months as $i => $m)
                                <option value="{{ $i + 1 }}" {{ (int)$bulan === $i + 1 ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tahun</label>
                        <select name="tahun" class="border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2">
                            @foreach($tahuns as $t)
                                <option value="{{ $t }}" {{ (int)$tahun === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Bidang</label>
                        <select name="bidang" class="border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2">
                            <option value="">Semua Bidang</option>
                            @foreach($bidangs as $b)
                                <option value="{{ $b }}" {{ $bidang === $b ? 'selected' : '' }}>{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition text-sm font-medium shadow-sm">Tampilkan</button>
                    <a href="{{ route('report.pdf', request()->only('bulan', 'tahun', 'bidang')) }}" class="px-5 py-2.5 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition text-sm font-medium shadow-sm">
                        Download PDF
                    </a>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <p class="text-sm text-gray-500 font-medium">Total Surat</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $total }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $months[(int)$bulan - 1] }} {{ $tahun }}</p>
                </div>
                @foreach($perJenis as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <p class="text-sm text-gray-500 font-medium">{{ $item->letterType->name ?? 'Jenis tidak ditemukan' }}</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $item->total }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $item->letterType->bidang ?? '-' }}</p>
                </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Daftar Surat</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-50">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembuat</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bidang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengolah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tujuan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
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
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $sub->pengolah ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $sub->ditujukan_kepada ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $sub->letter_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ ($sub->submission_date ?: $sub->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <p class="text-gray-400 font-medium">Tidak ada data untuk periode ini</p>
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
