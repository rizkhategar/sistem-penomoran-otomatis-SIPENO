<?php

namespace App\Http\Controllers;

use App\Models\LetterType;
use App\Models\MasterBidang;
use App\Models\MasterJenisSurat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LetterTypeController extends Controller
{
    public function index()
    {
        $letterTypes = LetterType::with(['creator', 'masterBidang', 'masterJenisSurat'])
            ->latest()
            ->paginate(10);

        return view('admin.letter-types.index', compact('letterTypes'));
    }

    public function create()
    {
        $bidangs = MasterBidang::where('is_active', true)->orderBy('name')->get();
        $jenisSurats = MasterJenisSurat::where('is_active', true)->orderBy('name')->get();

        return view('admin.letter-types.create', compact('bidangs', 'jenisSurats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_bidang_id' => ['required', 'exists:master_bidangs,id'],
            'master_jenis_surat_id' => ['required', 'exists:master_jenis_surats,id'],
            'description' => 'nullable|string|max:500',
            'monthly_quota' => 'nullable|integer|min:1',
            'daily_insertion' => 'nullable|integer|min:1|max:10',
        ]);

        $this->ensureCombinationIsUnique(
            (int) $request->master_bidang_id,
            (int) $request->master_jenis_surat_id
        );

        $bidang = MasterBidang::findOrFail($request->master_bidang_id);
        $jenisSurat = MasterJenisSurat::findOrFail($request->master_jenis_surat_id);

        LetterType::create([
            'master_bidang_id' => $bidang->id,
            'master_jenis_surat_id' => $jenisSurat->id,
            'name' => $jenisSurat->name,
            'code' => $this->makeLetterTypeCode($jenisSurat, $bidang),
            'bidang' => $bidang->name,
            'description' => $request->description ?: $jenisSurat->description,
            'created_by' => auth()->id(),
            'monthly_quota' => $request->monthly_quota ?? 5,
            'daily_insertion' => $request->daily_insertion ?? 5,
            'is_active' => true,
        ]);

        return redirect()->route('admin.letter-types.index')->with('success', 'Jenis surat berhasil ditambahkan.');
    }

    public function edit(LetterType $letterType)
    {
        $bidangs = MasterBidang::where('is_active', true)->orderBy('name')->get();
        $jenisSurats = MasterJenisSurat::where('is_active', true)->orderBy('name')->get();

        return view('admin.letter-types.edit', compact('letterType', 'bidangs', 'jenisSurats'));
    }

    public function update(Request $request, LetterType $letterType)
    {
        $request->validate([
            'master_bidang_id' => ['required', 'exists:master_bidangs,id'],
            'master_jenis_surat_id' => ['required', 'exists:master_jenis_surats,id'],
            'description' => 'nullable|string|max:500',
            'monthly_quota' => 'nullable|integer|min:1',
            'daily_insertion' => 'nullable|integer|min:1|max:10',
            'is_active' => 'boolean',
        ]);

        $this->ensureCombinationIsUnique(
            (int) $request->master_bidang_id,
            (int) $request->master_jenis_surat_id,
            $letterType->id
        );

        $bidang = MasterBidang::findOrFail($request->master_bidang_id);
        $jenisSurat = MasterJenisSurat::findOrFail($request->master_jenis_surat_id);

        $letterType->update([
            'master_bidang_id' => $bidang->id,
            'master_jenis_surat_id' => $jenisSurat->id,
            'name' => $jenisSurat->name,
            'code' => $this->makeLetterTypeCode($jenisSurat, $bidang),
            'bidang' => $bidang->name,
            'description' => $request->description ?: $jenisSurat->description,
            'monthly_quota' => $request->monthly_quota ?? 5,
            'daily_insertion' => $request->daily_insertion ?? 5,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.letter-types.index')->with('success', 'Jenis surat berhasil diupdate.');
    }

    public function destroy(LetterType $letterType)
    {
        if ($letterType->submissions()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus jenis surat yang sudah digunakan.');
        }

        $letterType->delete();

        return redirect()->route('admin.letter-types.index')->with('success', 'Jenis surat berhasil dihapus.');
    }

    private function ensureCombinationIsUnique(int $bidangId, int $jenisSuratId, ?int $ignoreId = null): void
    {
        $query = LetterType::where('master_bidang_id', $bidangId)
            ->where('master_jenis_surat_id', $jenisSuratId);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'master_jenis_surat_id' => 'Kombinasi bidang dan jenis surat ini sudah ada. Pilih kombinasi lain agar data tidak menumpuk.',
            ]);
        }
    }

    private function makeLetterTypeCode(MasterJenisSurat $jenisSurat, MasterBidang $bidang): string
    {
        $jenisCode = $jenisSurat->code ?: str($jenisSurat->name)->upper()->replace(' ', '-')->limit(20, '')->toString();
        $bidangCode = $bidang->code ?: str($bidang->name)->upper()->replace(' ', '-')->limit(10, '')->toString();

        return $jenisCode.'-'.$bidangCode;
    }
}
