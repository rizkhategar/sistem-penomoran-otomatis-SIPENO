<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Ajukan Surat Baru</h2>
            <p class="text-sm text-gray-500 mt-0.5">Lengkapi form di bawah untuk mengajukan nomor surat</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 sm:p-8">
                    <form action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                            <select name="letter_type_id" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                                <option value="">Pilih jenis surat</option>
                                @foreach($letterTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('letter_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }} ({{ $type->code }})</option>
                                @endforeach
                            </select>
                            @error('letter_type_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                            <textarea name="keperluan" rows="4" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" placeholder="Jelaskan keperluan pengajuan surat ini..." required>{{ old('keperluan') }}</textarea>
                            @error('keperluan') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Surat</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-blue-400 transition cursor-pointer" onclick="document.getElementById('fileInput').click()">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p class="text-sm text-gray-500 mb-1">Klik untuk upload file</p>
                                <p class="text-xs text-gray-400">Format: PDF, JPG, PNG. Maks 2MB</p>
                                <p id="fileName" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                            </div>
                            <input id="fileInput" type="file" name="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png" onchange="document.getElementById('fileName').textContent = this.files[0].name; document.getElementById('fileName').classList.remove('hidden')">
                            @error('file') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('submissions.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</a>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Ajukan Surat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
