<?php

namespace App\Http\Controllers;

use App\Models\LetterType;
use App\Models\MasterBidang;
use App\Models\MasterJenisSurat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterBidangController extends Controller
{
    public function index()
    {
        $bidangs = MasterBidang::withCount('letterTypes')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.master-bidangs.index', compact('bidangs'));
    }

    public function create()
    {
        return view('admin.master-bidangs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:master_bidangs,name',
            'code' => 'nullable|string|max:20|unique:master_bidangs,code',
        ]);

        $bidang = MasterBidang::create([
            'name' => strtoupper(trim($request->name)),
            'code' => $request->code ? strtoupper(trim($request->code)) : null,
            'is_active' => true,
        ]);

        $createdPairs = $this->makeAllActiveJenisSuratAvailableForBidang($bidang);

        return redirect()->route('admin.master-bidangs.index')
            ->with('success', 'Data bidang berhasil ditambahkan dan otomatis mendapat '.$createdPairs.' jenis surat aktif.');
    }

    public function edit(MasterBidang $masterBidang)
    {
        return view('admin.master-bidangs.edit', compact('masterBidang'));
    }

    public function update(Request $request, MasterBidang $masterBidang)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('master_bidangs', 'name')->ignore($masterBidang->id)],
            'code' => ['nullable', 'string', 'max:20', Rule::unique('master_bidangs', 'code')->ignore($masterBidang->id)],
            'is_active' => 'boolean',
        ]);

        $masterBidang->update([
            'name' => strtoupper(trim($request->name)),
            'code' => $request->code ? strtoupper(trim($request->code)) : null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($masterBidang->is_active) {
            $this->makeAllActiveJenisSuratAvailableForBidang($masterBidang);
        }

        return redirect()->route('admin.master-bidangs.index')->with('success', 'Data bidang berhasil diupdate.');
    }

    public function destroy(MasterBidang $masterBidang)
    {
        if ($masterBidang->letterTypes()->exists()) {
            return back()->with('error', 'Bidang tidak bisa dihapus karena sudah dipakai pada surat per bidang. Nonaktifkan saja jika tidak digunakan lagi.');
        }

        $masterBidang->delete();

        return redirect()->route('admin.master-bidangs.index')->with('success', 'Data bidang berhasil dihapus.');
    }

    private function makeAllActiveJenisSuratAvailableForBidang(MasterBidang $bidang): int
    {
        $count = 0;

        MasterJenisSurat::where('is_active', true)->get()->each(function (MasterJenisSurat $jenisSurat) use ($bidang, &$count) {
            $letterType = LetterType::updateOrCreate(
                [
                    'master_bidang_id' => $bidang->id,
                    'master_jenis_surat_id' => $jenisSurat->id,
                ],
                [
                    'name' => $jenisSurat->name,
                    'code' => $this->makeLetterTypeCode($jenisSurat, $bidang),
                    'bidang' => $bidang->name,
                    'description' => $jenisSurat->description,
                    'monthly_quota' => 5,
                    'daily_insertion' => 5,
                    'is_active' => true,
                ]
            );

            if ($letterType->wasRecentlyCreated) {
                $count++;
            }
        });

        return $count;
    }

    private function makeLetterTypeCode(MasterJenisSurat $jenisSurat, MasterBidang $bidang): string
    {
        $jenisCode = $jenisSurat->code ?: str($jenisSurat->name)->upper()->replace(' ', '-')->limit(20, '')->toString();
        $bidangCode = $bidang->code ?: str($bidang->name)->upper()->replace(' ', '-')->limit(10, '')->toString();

        return $jenisCode.'-'.$bidangCode;
    }
}
