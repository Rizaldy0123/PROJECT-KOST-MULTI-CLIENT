<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MaintenanceTicket;
use App\Models\ClientUser;
use App\Models\Notification;

class PenghuniDashboardController extends Controller
{
    public function index()
    {
        // Mengambil akun penghuni yang sedang login
        $user = auth()->user();

        $clientUser = ClientUser::where('user_id', $user->id)
            ->where('role', 'penghuni')
            ->where('is_active', 1)
            ->firstOrFail();

        // Mengambil kontrak dan informasi kamar milik penghuni
        $contract = $clientUser->contracts()
            ->with(['room.roomType', 'invoices.payments'])
            ->where('status', 'aktif')
            ->latest('id')
            ->first();

        // Mengambil riwayat keluhan milik penghuni
        $tickets = MaintenanceTicket::where('client_id', $clientUser->client_id)
            ->where('pelapor_id', $clientUser->id)
            ->latest('tanggal_lapor')
            ->get();

        // Mengambil notifikasi milik penghuni
        $notifications = Notification::where('client_id', $clientUser->client_id)
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->get();

        // Mengambil riwayat pembayaran dari invoice kontrak
        $payments = $contract
            ? $contract->invoices->flatMap(fn ($invoice) => $invoice->payments)
            : collect();

        return view('penghuni.dashboard', compact(
            'user',
            'clientUser',
            'contract',
            'tickets',
            'notifications',
            'payments'
        ));
    }

    // Menyimpan keluhan penghuni
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:500',
        ]);

        $user = auth()->user();

        $clientUser = ClientUser::where('user_id', $user->id)
            ->where('role', 'penghuni')
            ->where('is_active', 1)
            ->firstOrFail();

        $contract = $clientUser->contracts()
            ->where('status', 'aktif')
            ->firstOrFail();

        MaintenanceTicket::create([
            'client_id' => $clientUser->client_id,
            'room_id' => $contract->room_id,
            'pelapor_id' => $clientUser->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('penghuni.dashboard')
            ->with('success', 'Keluhan berhasil diajukan!');
    }
}

