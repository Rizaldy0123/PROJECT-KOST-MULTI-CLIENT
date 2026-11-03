<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceTicket;
use App\Models\Room;
use App\Models\ClientUser; // Ubah dari User ke ClientUser
use Illuminate\Http\Request;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    public function index()
    {
        $tickets = MaintenanceTicket::with(['room.property', 'pelapor'])
            ->orderBy('tanggal_lapor', 'desc')
            ->get();

        return view('admin.maintenance.index', compact('tickets'));
    }

    public function create()
    {
        $rooms = Room::with('property')->get();
        // Ambil data dari client_users beserta relasi user-nya
        $users = ClientUser::with('user')->get();

        return view('admin.maintenance.create', compact('rooms', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'    => 'required|exists:rooms,id',
            'pelapor_id' => 'required|exists:client_users,id', // Validasi ke tabel client_users
            'judul'      => 'required|string|max:100',
            'deskripsi'  => 'required|string|max:500',
            'status'     => 'required|in:diajukan,diproses,selesai,ditolak',
        ]);

        // Ambil client_id otomatis dari properti kamar
        $room = Room::with('property')->findOrFail($validated['room_id']);
        $validated['client_id'] = $room->property->client_id ?? 1;
        
        // Set tanggal pelaporan
        $validated['tanggal_lapor'] = Carbon::now();

        MaintenanceTicket::create($validated);

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Tiket perbaikan berhasil dibuat!');
    }

    public function edit(MaintenanceTicket $maintenance)
    {
        $rooms = Room::with('property')->get();
        $users = ClientUser::with('user')->get();

        return view('admin.maintenance.edit', compact('maintenance', 'rooms', 'users'));
    }

    public function update(Request $request, MaintenanceTicket $maintenance)
    {
        $validated = $request->validate([
            'room_id'    => 'required|exists:rooms,id',
            'pelapor_id' => 'required|exists:client_users,id', // Validasi ke tabel client_users
            'judul'      => 'required|string|max:100',
            'deskripsi'  => 'required|string|max:500',
            'status'     => 'required|in:diajukan,diproses,selesai,ditolak',
        ]);

        $room = Room::with('property')->findOrFail($validated['room_id']);
        $validated['client_id'] = $room->property->client_id ?? 1;

        $maintenance->update($validated);

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Status tiket perbaikan berhasil diperbarui!');
    }

    public function destroy(MaintenanceTicket $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Tiket perbaikan berhasil dihapus!');
    }
}