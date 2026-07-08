<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Report Bulanan</h2>
                <p class="text-sm text-gray-500 mt-0.5">Laporan surat per bulan yang bisa diunduh sebagai PDF</p>
            </div>
            <div class="text-sm text-gray-500">
                Periode: <span class="font-semibold text-gray-700">{{ $months[(int)$bulan - 1] }} {{ $tahun }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Filter Laporan</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Pilih bulan, tahun, dan bidang untuk melihat data.</p>
                    </div>

                    <form method="GET" action="{{ route('report.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[120px_120px_320px_auto_auto] gap-3 lg:items-end w-full lg:w-auto">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
                            <select name="bulan" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-3 py-2.5 text-sm">
                                @foreach($months as $i => $m)
                                    <option value="{{ $i + 1 }}" {{ (int)$bulan === $i + 1 ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tahun</label>
                            <select name="tahun" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-3 py-2.5 text-sm">
                                @foreach($tahuns as $t)
                                    <option value="{{ $t }}" {{ (int)$tahun === $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2 lg:col-span-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bidang</label>
                            <select name="bidang" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-3 py-2.5 text-sm">
                                <option value="">Semua Bidang</option>
                                @foreach($bidangs as $b)
                                    <option value="{{ $b }}" {{ $bidang === $b ? 'selected' : '' }}>{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full lg:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition text-sm font-medium shadow-sm">
                            Tampilkan
                        </button>
                        <a href="{{ route('report.pdf', request()->only('bulan', 'tahun', 'bidang')) }}" class="w-full lg:w-auto text-center px-5 py-2.5 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition text-sm font-medium shadow-sm">
                            Download PDF
                        </a>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 min-h-[116px] flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Surat</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $total }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $months[(int)$bulan - 1] }} {{ $tahun }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>

                @foreach($perJenis as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 min-h-[116px]">
                    <p class="text-sm text-gray-600 font-semibold leading-snug line-clamp-2">{{ $item->letterType->name ?? 'Jenis tidak ditemukan' }}</p>
                    <div class="flex items-end justify-between mt-3">
                        <div>
                            <p class="text-3xl font-bold text-indigo-600">{{ $item->total }}</p>
                            <p class="text-xs text-gray-400 mt-1 uppercase tracking-wide">{{ $item->letterType->bidang ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">Daftar Surat</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Menampilkan data surat sesuai filter laporan.</p>
                    </div>
                    <span class="text-xs text-gray-500 bg-gray-50 border border-gray-100 rounded-full px-3 py-1">
                        {{ $submissions->total() }} data
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[170px]">Pembuat</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[220px]">Jenis</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[170px]">Bidang</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[150px]">Pengolah</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[190px]">Tujuan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[170px]">No. Surat</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider min-w-[110px]">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($submissions as $index => $sub)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $submissions->firstItem() + $index }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0">{{ substr($sub->user->name, 0, 1) }}</div>
                                        <span class="font-medium text-gray-800 text-sm whitespace-nowrap">{{ $sub->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $sub->letterType->name }}</td>
                                <td class="px-5 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full whitespace-nowrap">{{ $sub->letterType->bidang ?? '-' }}</span></td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $sub->pengolah ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-gray-600 max-w-xs truncate">{{ $sub->ditujukan_kepada ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm font-semibold text-gray-800 whitespace-nowrap">{{ $sub->letter_number }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500 whitespace-nowrap">{{ ($sub->submission_date ?: $sub->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-14 text-center">
                                    <div class="mx-auto w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="text-gray-400 font-medium">Tidak ada data untuk periode ini</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($submissions->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">{{ $submissions->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
