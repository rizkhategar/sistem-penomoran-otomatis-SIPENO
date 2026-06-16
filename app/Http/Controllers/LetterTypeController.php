<?php

namespace App\Http\Controllers;

use App\Models\LetterType;
use Illuminate\Http\Request;

class LetterTypeController extends Controller
{
    public function index()
    {
        $letterTypes = LetterType::latest()->paginate(10);
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
            'description' => 'nullable|string|max:500',
        ]);

        LetterType::create($request->all());

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
            'description' => 'nullable|string|max:500',
        ]);

        $letterType->update($request->all());

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
