<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">Selamat datang, {{ auth()->user()->name }}
                    @if(auth()->user()->bidang)
                        <span class="ml-1 text-blue-500">({{ auth()->user()->bidang }})</span>
                    @endif
                </p>
            </div>
            @if(!auth()->user()->isAdmin())
                <a href="{{ route('submissions.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-sm text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Surat Baru
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

            @if(auth()->user()->isAdmin())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Total Surat</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                        </div>
                    </div>
                    @foreach($bidangStats as $b => $count)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">{{ $b }}</p>
                                <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $count }}</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="grid lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 mb-1">Surat Per Bulan ({{ date('Y') }})</h3>
                        <p class="text-xs text-gray-400 mb-4">Total surat setiap bulan</p>
                        <canvas id="monthlyChart" height="200"></canvas>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-800 mb-1">Per Jenis Surat</h3>
                        <p class="text-xs text-gray-400 mb-4">Distribusi surat berdasarkan jenis</p>
                        <canvas id="typeChart" height="200"></canvas>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
                <script>
                    new Chart(document.getElementById('monthlyChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($monthlyLabels),
                            datasets: [{
                                label: 'Surat',
                                data: @json($monthlyData),
                                backgroundColor: '#3b82f6',
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });

                    new Chart(document.getElementById('typeChart'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($typeLabels),
                            datasets: [{
                                data: @json($typeData),
                                backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'],
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                                }
                            }
                        }
                    });
                </script>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Surat Terbaru</h3>
                        <a href="{{ route('admin.submissions.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-50">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembuat</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bidang</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($submissions as $sub)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xs font-bold">{{ substr($sub->user->name, 0, 1) }}</div>
                                            <span class="font-medium text-gray-800 text-sm">{{ $sub->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sub->letterType->name }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $sub->letter_number }}</td>
                                    <td class="px-6 py-4"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $sub->letterType->bidang ?? '-' }}</span></td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ ($sub->submission_date ?: $sub->created_at)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.submissions.show', $sub) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p class="text-sm">Belum ada surat</p>
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

            @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Semua Surat Terdaftar</p>
                            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $bidangTotal }} Surat</p>
                        </div>
                        <a href="{{ route('report.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Report &rarr;</a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-gray-800">Semua Surat Yang Diajukan</h3>
                            <p class="text-sm text-gray-400 mt-0.5">Total {{ $submissions->total() }} surat dari seluruh user</p>
                        </div>
                        <a href="{{ route('submissions.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-50">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembuat</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keperluan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($submissions as $sub)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sub->user->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-800">{{ $sub->letterType->name }}</span>
                                        <span class="text-xs text-gray-400 block">{{ $sub->letterType->code }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $sub->keperluan }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $sub->letter_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ ($sub->submission_date ?: $sub->created_at)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('submissions.show', $sub) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <svg class="w-14 h-14 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p class="text-gray-400 font-medium mb-1">Belum ada surat</p>
                                        <p class="text-gray-400 text-sm mb-4">Belum ada user yang membuat surat.</p>
                                        <a href="{{ route('submissions.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition text-sm font-medium shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Buat Surat Sekarang
                                        </a>
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
            @endif
        </div>
    </div>
</x-app-layout>
