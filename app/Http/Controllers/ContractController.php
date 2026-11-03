<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Room;
use App\Models\ClientUser; // Ubah dari User ke ClientUser
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::whereHas('room.property')
            ->with(['room.property', 'user.user']) // Sesuaikan relasi ke clientUser
            ->latest()
            ->get();

        return view('admin.contracts.index', compact('contracts'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'tersedia')->with('property')->get();
        // Ambil data dari client_users beserta data user-nya
        $users = ClientUser::with('user')->get();

        return view('admin.contracts.create', compact('rooms', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'client_user_id' => 'required|exists:client_users,id', // Validasi ke tabel client_users
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'status'         => 'required|in:aktif,selesai,dibatalkan',
        ]);

        // Otomatis masukkan client_id dari properti kamar yang dipilih
        $room = Room::with('property')->findOrFail($validated['room_id']);
        $validated['client_id'] = $room->property->client_id ?? 1;

        Contract::create($validated);

        if ($validated['status'] === 'aktif') {
            Room::where('id', $validated['room_id'])->update(['status' => 'terisi']);
        }

        return redirect()->route('admin.contracts.index')
            ->with('success', 'Kontrak sewa berhasil dibuat!');
    }

    public function edit(Contract $contract)
    {
        $rooms = Room::with('property')->get();
        $users = ClientUser::with('user')->get();

        return view('admin.contracts.edit', compact('contract', 'rooms', 'users'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'client_user_id' => 'required|exists:client_users,id', // Validasi ke tabel client_users
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'status'         => 'required|in:aktif,selesai,dibatalkan',
        ]);

        $oldRoomId = $contract->room_id;
        
        $room = Room::with('property')->findOrFail($validated['room_id']);
        $validated['client_id'] = $room->property->client_id ?? 1;

        $contract->update($validated);

        if ($oldRoomId != $validated['room_id']) {
            Room::where('id', $oldRoomId)->update(['status' => 'tersedia']);
        }

        if ($validated['status'] === 'aktif') {
            Room::where('id', $validated['room_id'])->update(['status' => 'terisi']);
        } else {
            Room::where('id', $validated['room_id'])->update(['status' => 'tersedia']);
        }

        return redirect()->route('admin.contracts.index')
            ->with('success', 'Data kontrak sewa berhasil diperbarui!');
    }

    public function destroy(Contract $contract)
    {
        Room::where('id', $contract->room_id)->update(['status' => 'tersedia']);
        $contract->delete();

        return redirect()->route('admin.contracts.index')
            ->with('success', 'Kontrak sewa berhasil dihapus!');
    }
}