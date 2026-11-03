<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\MaintenanceTicket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminIndex()
    {
        // 1. Ringkasan Properti & Kamar
        $totalProperties = Property::count();
        $totalRooms = Room::whereHas('property')->count();
        $kamarTerisi = Room::whereHas('property')->where('status', 'terisi')->count();
        $kamarTersedia = Room::whereHas('property')->where('status', 'tersedia')->count();

        // 2. Ringkasan Kontrak & Keuangan
        $kontrakAktif = Contract::whereHas('room.property')->where('status', 'aktif')->count();

        $pendapatanBulanIni = Invoice::whereHas('contract.room.property')
            ->where('status', 'lunas')
            ->whereMonth('created_at', now()->month)
            ->sum('jumlah');

        // Tambahan: Total Tagihan Belum Lunas (Piutang)
        $totalPiutang = Invoice::whereHas('contract.room.property')
            ->where('status', 'belum_lunas')
            ->sum('jumlah');

        // 3. Tiket Perbaikan & Tagihan Terbaru
        $tiketAktif = MaintenanceTicket::whereHas('room.property')
            ->whereIn('status', ['diajukan', 'diproses'])
            ->count();

        $tiketTerbaru = MaintenanceTicket::whereHas('room.property')
            ->with('room')
            ->latest('tanggal_lapor')
            ->take(5)
            ->get();

        $tagihanTerbaru = Invoice::whereHas('contract.room.property')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProperties',
            'totalRooms',
            'kamarTerisi',
            'kamarTersedia',
            'kontrakAktif',
            'pendapatanBulanIni',
            'totalPiutang',
            'tiketAktif',
            'tiketTerbaru',
            'tagihanTerbaru'
        ));
    }
}