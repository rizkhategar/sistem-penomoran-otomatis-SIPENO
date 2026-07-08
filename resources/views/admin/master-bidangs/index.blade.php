<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Master Bidang</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola daftar bidang yang dipakai di user dan surat</p>
            </div>
            <a href="{{ route('admin.master-bidangs.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-sm text-sm font-medium">
                Tambah Bidang
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Bidang</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Relasi</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($bidangs as $bidang)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 text-sm font-mono font-semibold text-blue-700">{{ $bidang->code ?? '-' }}</td>
                                    <td class="px-5 py-4 text-sm font-semibold text-gray-800">{{ $bidang->name }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-500">{{ $bidang->letter_types_count }} jenis surat</td>
                                    <td class="px-5 py-4">
                                        <span class="text-xs px-2.5 py-1 rounded-full {{ $bidang->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                                            {{ $bidang->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.master-bidangs.edit', $bidang) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Edit</a>
                                            <form action="{{ route('admin.master-bidangs.destroy', $bidang) }}" method="POST" onsubmit="return confirm('Hapus bidang ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada master bidang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($bidangs->hasPages())
                <div class="mt-6">{{ $bidangs->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
