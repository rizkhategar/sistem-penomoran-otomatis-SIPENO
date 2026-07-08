<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Edit Jenis Surat</h2>
            <p class="text-sm text-gray-500 mt-0.5">Ubah relasi bidang dan jenis surat dari master data</p>
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
                    <form action="{{ route('admin.letter-types.update', $letterType) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bidang</label>
                            <select name="master_bidang_id" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                                <option value="">Pilih bidang</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->id }}" {{ old('master_bidang_id', $letterType->master_bidang_id) == $bidang->id ? 'selected' : '' }}>{{ $bidang->name }}</option>
                                @endforeach
                            </select>
                            @error('master_bidang_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                            <select name="master_jenis_surat_id" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                                <option value="">Pilih jenis surat</option>
                                @foreach($jenisSurats as $jenisSurat)
                                    <option value="{{ $jenisSurat->id }}" {{ old('master_jenis_surat_id', $letterType->master_jenis_surat_id) == $jenisSurat->id ? 'selected' : '' }}>{{ $jenisSurat->name }}</option>
                                @endforeach
                            </select>
                            @error('master_jenis_surat_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Surat</label>
                            <input type="text" value="{{ $letterType->code }}" class="w-full border-gray-200 rounded-xl shadow-sm bg-gray-100 px-4 py-2.5 text-gray-500" readonly>
                            <p class="text-xs text-gray-400 mt-1.5">Kode surat dibuat otomatis dari master jenis surat dan master bidang.</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-gray-400">(opsional)</span></label>
                            <textarea name="description" rows="3" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5">{{ old('description', $letterType->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kuota Bulanan</label>
                                <input type="number" name="monthly_quota" value="{{ old('monthly_quota', $letterType->monthly_quota) }}" min="1" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sisipan Per Hari</label>
                                <input type="number" name="daily_insertion" value="{{ old('daily_insertion', $letterType->daily_insertion) }}" min="1" max="10" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5">
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $letterType->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Aktif</span>
                            </label>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.letter-types.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</a>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
