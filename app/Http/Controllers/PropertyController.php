<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
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
            'alamat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $clientId = session('active_client_id')
            ?? $request->user()?->client_id;

        abort_if(
            ! $clientId,
            403,
            'Client aktif belum ditentukan.'
        );

        $validated['client_id'] = $clientId;

        // Upload foto properti
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                ->store('properties', 'public');
        }

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
            'alamat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Jika admin mengunggah foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto sebelumnya
            if ($property->foto) {
                Storage::disk('public')->delete($property->foto);
            }

            // Simpan foto baru
            $validated['foto'] = $request->file('foto')
                ->store('properties', 'public');
        }

        $property->update($validated);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Data properti berhasil diperbarui!');
    }

    public function destroy(Property $property)
    {
        // Hapus foto properti jika tersedia
        if ($property->foto) {
            Storage::disk('public')->delete($property->foto);
        }

        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Properti berhasil dihapus!');
    }
}
