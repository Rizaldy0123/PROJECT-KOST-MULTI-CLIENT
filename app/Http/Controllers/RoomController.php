<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::whereHas('property')
            ->with(['property', 'roomType'])
            ->latest()
            ->get();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $properties = Property::where('status', 'aktif')->get();
        $roomTypes = RoomType::all();
        return view('admin.rooms.create', compact('properties', 'roomTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id'   => 'required|exists:properties,id',
            'tipe_kamar_id' => 'required|exists:room_types,id',
            'nomor_kamar'   => 'required|string|max:10',
            'lantai'        => 'required|integer|min:1',
            'harga'         => 'required|numeric|min:0', // Sesuaikan dengan kolom database Anda ('harga')
            'status'        => 'required|in:tersedia,terisi,perbaikan',
        ]);

        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Unit kamar berhasil ditambahkan!');
    }

    public function edit(Room $room)
    {
        $properties = Property::where('status', 'aktif')->get();
        $roomTypes = RoomType::all();
        return view('admin.rooms.edit', compact('room', 'properties', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'property_id'   => 'required|exists:properties,id',
            'tipe_kamar_id' => 'required|exists:room_types,id',
            'nomor_kamar'   => 'required|string|max:10',
            'lantai'        => 'required|integer|min:1',
            'harga'         => 'required|numeric|min:0', // Sesuaikan dengan kolom database Anda ('harga')
            'status'        => 'required|in:tersedia,terisi,perbaikan',
        ]);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Data unit kamar berhasil diperbarui!');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Unit kamar berhasil dihapus!');
    }
}