<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->latest()->get();
        return view('admin.room_types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('admin.room_types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tipe'     => 'required|string|max:50',
            'harga_default' => 'required|numeric|min:0',
            'kapasitas'     => 'required|integer|min:1',
            'deskripsi'     => 'nullable|string',
        ]);

        RoomType::create($validated);

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar berhasil ditambahkan!');
    }

    public function edit(RoomType $roomType)
    {
        return view('admin.room_types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'nama_tipe'     => 'required|string|max:50',
            'harga_default' => 'required|numeric|min:0',
            'kapasitas'     => 'required|integer|min:1',
            'deskripsi'     => 'nullable|string',
        ]);

        $roomType->update($validated);

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar berhasil diperbarui!');
    }

    public function destroy(RoomType $roomType)
    {
        $roomType->delete();

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar berhasil dihapus!');
    }
}