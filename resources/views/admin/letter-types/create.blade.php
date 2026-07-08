<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Tambah Jenis Surat</h2>
            <p class="text-sm text-gray-500 mt-0.5">Pilih bidang dan jenis surat dari master data</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
                    <p class="font-semibold text-sm mb-2">Data belum lengkap:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form action="{{ route('admin.letter-types.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bidang</label>
                            <select name="master_bidang_id" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                                <option value="">Pilih bidang</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->id }}" {{ old('master_bidang_id') == $bidang->id ? 'selected' : '' }}>{{ $bidang->name }}</option>
                                @endforeach
                            </select>
                            @error('master_bidang_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                            <select name="master_jenis_surat_id" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                                <option value="">Pilih jenis surat</option>
                                @foreach($jenisSurats as $jenisSurat)
                                    <option value="{{ $jenisSurat->id }}" {{ old('master_jenis_surat_id') == $jenisSurat->id ? 'selected' : '' }}>{{ $jenisSurat->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1.5">Data bidang dan jenis surat berasal dari master data, sehingga tidak perlu mengetik ulang.</p>
                            @error('master_jenis_surat_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-gray-400">(opsional)</span></label>
                            <textarea name="description" rows="3" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" placeholder="Kosongkan jika memakai deskripsi dari master jenis surat">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kuota Bulanan</label>
                                <input type="number" name="monthly_quota" value="{{ old('monthly_quota', 5) }}" min="1" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sisipan Per Hari</label>
                                <input type="number" name="daily_insertion" value="{{ old('daily_insertion', 5) }}" min="1" max="10" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5">
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.letter-types.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</a>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
