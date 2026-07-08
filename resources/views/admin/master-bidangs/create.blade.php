<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Tambah Data Bidang</h2>
            <p class="text-sm text-gray-500 mt-0.5">Bidang baru otomatis mendapat semua jenis surat aktif</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6 bg-blue-50 border border-blue-100 rounded-2xl p-5 text-sm text-blue-900">
                Setelah disimpan, bidang ini langsung mendapat semua jenis surat yang statusnya aktif. Pengaturan per bidang tetap bisa dilihat atau diubah di menu <b>Surat per Bidang</b>.
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <form action="{{ route('admin.master-bidangs.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bidang</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Bidang</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5">
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.master-bidangs.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
