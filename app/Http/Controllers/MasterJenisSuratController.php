<?php

namespace App\Http\Controllers;

use App\Models\LetterType;
use App\Models\MasterBidang;
use App\Models\MasterJenisSurat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterJenisSuratController extends Controller
{
    public function index()
    {
        $this->syncAllActiveJenisSuratToActiveBidangs();

        $activeBidangIds = MasterBidang::where('is_active', true)->select('id');
        $activeBidangCount = MasterBidang::where('is_active', true)->count();

        $jenisSurats = MasterJenisSurat::query()
            ->addSelect([
                'active_bidangs_count' => LetterType::query()
                    ->selectRaw('COUNT(DISTINCT master_bidang_id)')
                    ->whereColumn('master_jenis_surat_id', 'master_jenis_surats.id')
                    ->where('is_active', true)
                    ->whereNotNull('master_bidang_id')
                    ->whereIn('master_bidang_id', $activeBidangIds),
            ])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.master-jenis-surats.index', compact('jenisSurats', 'activeBidangCount'));
    }

    public function create()
    {
        return view('admin.master-jenis-surats.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:master_jenis_surats,name',
            'code' => 'nullable|string|max:50|unique:master_jenis_surats,code',
            'description' => 'nullable|string|max:500',
        ]);

        $jenisSurat = MasterJenisSurat::create([
            'name' => trim($request->name),
            'code' => $request->code ? strtoupper(trim($request->code)) : null,
            'description' => $request->description,
            'is_active' => true,
        ]);

        $this->makeAvailableForAllActiveBidangs($jenisSurat);
        $activeBidangCount = MasterBidang::where('is_active', true)->count();

        return redirect()->route('admin.master-jenis-surats.index')
            ->with('success', 'Data jenis surat berhasil ditambahkan dan otomatis tersedia pada '.$activeBidangCount.' bidang aktif.');
    }

    public function edit(MasterJenisSurat $masterJenisSurat)
    {
        return view('admin.master-jenis-surats.edit', compact('masterJenisSurat'));
    }

    public function update(Request $request, MasterJenisSurat $masterJenisSurat)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('master_jenis_surats', 'name')->ignore($masterJenisSurat->id)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('master_jenis_surats', 'code')->ignore($masterJenisSurat->id)],
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $masterJenisSurat->update([
            'name' => trim($request->name),
            'code' => $request->code ? strtoupper(trim($request->code)) : null,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($masterJenisSurat->is_active) {
            $this->makeAvailableForAllActiveBidangs($masterJenisSurat);
        }

        $this->syncExistingLetterTypeData($masterJenisSurat);

        return redirect()->route('admin.master-jenis-surats.index')->with('success', 'Data jenis surat berhasil diupdate.');
    }

    public function destroy(MasterJenisSurat $masterJenisSurat)
    {
        if ($masterJenisSurat->letterTypes()->exists()) {
            return back()->with('error', 'Jenis surat tidak bisa dihapus karena sudah digunakan. Nonaktifkan saja jika tidak digunakan lagi.');
        }

        $masterJenisSurat->delete();

        return redirect()->route('admin.master-jenis-surats.index')->with('success', 'Data jenis surat berhasil dihapus.');
    }

    private function syncAllActiveJenisSuratToActiveBidangs(): void
    {
        MasterJenisSurat::where('is_active', true)->get()->each(function (MasterJenisSurat $jenisSurat) {
            $this->makeAvailableForAllActiveBidangs($jenisSurat);
        });
    }

    private function makeAvailableForAllActiveBidangs(MasterJenisSurat $jenisSurat): void
    {
        MasterBidang::where('is_active', true)->get()->each(function (MasterBidang $bidang) use ($jenisSurat) {
            LetterType::firstOrCreate(
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
        });
    }

    private function syncExistingLetterTypeData(MasterJenisSurat $jenisSurat): void
    {
        $jenisSurat->letterTypes()
            ->with('masterBidang')
            ->get()
            ->each(function (LetterType $letterType) use ($jenisSurat) {
                if (!$letterType->masterBidang) {
                    return;
                }

                $letterType->update([
                    'name' => $jenisSurat->name,
                    'code' => $this->makeLetterTypeCode($jenisSurat, $letterType->masterBidang),
                    'description' => $jenisSurat->description,
                    'bidang' => $letterType->masterBidang->name,
                ]);
            });
    }

    private function makeLetterTypeCode(MasterJenisSurat $jenisSurat, MasterBidang $bidang): string
    {
        $jenisCode = $jenisSurat->code ?: str($jenisSurat->name)->upper()->replace(' ', '-')->limit(20, '')->toString();
        $bidangCode = $bidang->code ?: str($bidang->name)->upper()->replace(' ', '-')->limit(10, '')->toString();

        return $jenisCode.'-'.$bidangCode;
    }
}
