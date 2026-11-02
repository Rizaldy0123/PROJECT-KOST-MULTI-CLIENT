<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        // Trait BelongsToClient otomatis menyaring properti milik client_id yang sedang login
        $properties = Property::withCount('rooms')->latest()->get();
        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        return view('admin.properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_properti' => 'required|string|max:100',
            'alamat'        => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        // Trait BelongsToClient otomatis menyisipkan client_id saat simpan data
        Property::create($validated);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Properti baru berhasil ditambahkan!');
    }

    public function edit(Property $property)
    {
        return view('admin.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'nama_properti' => 'required|string|max:100',
            'alamat'        => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $property->update($validated);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Data properti berhasil diperbarui!');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Properti berhasil dihapus!');
    }
}