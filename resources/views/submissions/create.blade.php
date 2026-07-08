<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Buat Surat Baru</h2>
            <p class="text-sm text-gray-500 mt-0.5">Lengkapi form di bawah untuk membuat surat</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
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
                    <form action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bidang</label>
                            <select id="bidangSelect" name="bidang" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                                <option value="">Pilih bidang</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang }}" {{ old('bidang') === $bidang ? 'selected' : '' }}>{{ $bidang }}</option>
                                @endforeach
                            </select>
                            @error('bidang') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Surat</label>
                            <select id="letterTypeSelect" name="letter_type_id" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" required>
                                <option value="">Pilih jenis surat</option>
                                @foreach($letterTypes as $type)
                                    <option value="{{ $type->id }}" data-bidang="{{ $type->bidang }}" {{ old('letter_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1.5">Pilih bidang terlebih dahulu agar jenis surat sesuai bidangnya.</p>
                            @error('letter_type_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Format Nomor Surat</label>
                            <input
                                type="text"
                                name="number_format"
                                value="{{ old('number_format') }}"
                                class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5"
                                placeholder="Contoh: 470/800/00.1.2.3"
                                autocomplete="off"
                                required
                            >
                            <p class="text-xs text-gray-400 mt-1.5">Isi manual sesuai format yang dibutuhkan. Contoh hasil nomor: 001/470/800/00.1.2.3</p>
                            @error('number_format') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pengolah</label>
                                <input type="text" name="pengolah" value="{{ old('pengolah') }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" placeholder="Nama/staf pengolah" required>
                                @error('pengolah') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ditujukan Kepada</label>
                                <input type="text" name="ditujukan_kepada" value="{{ old('ditujukan_kepada') }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" placeholder="Kepada siapa surat dituju" required>
                                @error('ditujukan_kepada') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                            <textarea name="keperluan" rows="4" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 px-4 py-2.5" placeholder="Jelaskan keperluan surat ini..." required>{{ old('keperluan') }}</textarea>
                            @error('keperluan') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File (opsional)</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-blue-400 transition cursor-pointer" onclick="document.getElementById('fileInput').click()">
                                <p class="text-sm text-gray-500 mb-1">Klik untuk upload file</p>
                                <p class="text-xs text-gray-400">Format: PDF, JPG, PNG. Maks 2MB</p>
                                <p id="fileName" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                            </div>
                            <input id="fileInput" type="file" name="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png" onchange="if (this.files[0]) { document.getElementById('fileName').textContent = this.files[0].name; document.getElementById('fileName').classList.remove('hidden'); }">
                            @error('file') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6 p-4 bg-gray-50 rounded-xl">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="is_sk" value="1" {{ old('is_sk') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">Gunakan tanggal mundur / sisipan nomor</span>
                            </label>
                            <p class="text-xs text-gray-400 mt-2">Maksimal 5 nomor sisipan mundur per tanggal.</p>
                            <div id="skDateField" class="mt-3 {{ old('is_sk') ? '' : 'hidden' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat</label>
                                <input type="date" name="submission_date" value="{{ old('submission_date') }}" max="{{ now()->toDateString() }}" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white px-4 py-2.5">
                            </div>
                            @error('submission_date') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <script>
                            const bidangSelect = document.getElementById('bidangSelect');
                            const letterTypeSelect = document.getElementById('letterTypeSelect');
                            const allLetterTypeOptions = Array.from(letterTypeSelect.options).map(option => option.cloneNode(true));

                            function filterLetterTypes() {
                                const selectedBidang = bidangSelect.value;
                                const selectedValue = letterTypeSelect.value;
                                letterTypeSelect.innerHTML = '';

                                allLetterTypeOptions.forEach(option => {
                                    if (!option.value || option.dataset.bidang === selectedBidang) {
                                        letterTypeSelect.appendChild(option.cloneNode(true));
                                    }
                                });

                                if ([...letterTypeSelect.options].some(option => option.value === selectedValue)) {
                                    letterTypeSelect.value = selectedValue;
                                }
                            }

                            bidangSelect?.addEventListener('change', filterLetterTypes);
                            filterLetterTypes();

                            const skCheckbox = document.querySelector('[name="is_sk"]');
                            skCheckbox?.addEventListener('change', function() {
                                document.getElementById('skDateField').classList.toggle('hidden', !this.checked);
                            });
                        </script>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('submissions.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</a>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">Buat Surat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
