<?php

namespace App\Http\Controllers;

use App\Models\MasterJenisSurat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterJenisSuratController extends Controller
{
    public function index()
    {
        $jenisSurats = MasterJenisSurat::withCount('letterTypes')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.master-jenis-surats.index', compact('jenisSurats'));
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

        MasterJenisSurat::create([
            'name' => trim($request->name),
            'code' => $request->code ? strtoupper(trim($request->code)) : null,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('admin.master-jenis-surats.index')->with('success', 'Data jenis surat berhasil ditambahkan.');
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

        return redirect()->route('admin.master-jenis-surats.index')->with('success', 'Data jenis surat berhasil diupdate.');
    }

    public function destroy(MasterJenisSurat $masterJenisSurat)
    {
        if ($masterJenisSurat->letterTypes()->exists()) {
            return back()->with('error', 'Jenis surat tidak bisa dihapus karena sudah dipakai pada surat per bidang. Nonaktifkan saja jika tidak digunakan lagi.');
        }

        $masterJenisSurat->delete();

        return redirect()->route('admin.master-jenis-surats.index')->with('success', 'Data jenis surat berhasil dihapus.');
    }
}
