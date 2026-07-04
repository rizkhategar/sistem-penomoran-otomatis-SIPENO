<?php

namespace App\Http\Controllers;

use App\Models\LetterType;
use Illuminate\Http\Request;

class LetterTypeController extends Controller
{
    public function index()
    {
        $letterTypes = LetterType::with('creator')->latest()->paginate(10);
        return view('admin.letter-types.index', compact('letterTypes'));
    }

    public function create()
    {
        return view('admin.letter-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:letter_types,code',
            'bidang' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'monthly_quota' => 'nullable|integer|min:1',
            'daily_insertion' => 'nullable|integer|min:1',
        ]);

        LetterType::create([
            'name' => $request->name,
            'code' => $request->code,
            'bidang' => $request->bidang,
            'description' => $request->description,
            'created_by' => auth()->id(),
            'monthly_quota' => $request->monthly_quota ?? 5,
            'daily_insertion' => $request->daily_insertion ?? 5,
            'is_active' => true,
        ]);

        return redirect()->route('admin.letter-types.index')->with('success', 'Jenis surat berhasil ditambahkan.');
    }

    public function edit(LetterType $letterType)
    {
        return view('admin.letter-types.edit', compact('letterType'));
    }

    public function update(Request $request, LetterType $letterType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:letter_types,code,' . $letterType->id,
            'bidang' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'monthly_quota' => 'nullable|integer|min:1',
            'daily_insertion' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $letterType->update($request->only([
            'name', 'code', 'bidang', 'description',
            'monthly_quota', 'daily_insertion', 'is_active'
        ]));

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
}
