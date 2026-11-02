<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Room;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\MaintenanceTicket;

class DashboardController extends Controller
{
    public function adminIndex()
    {
        // Karena Trait BelongsToClient sudah terpasang, query berikut otomatis terfilter berdasarkan client_id aktif

        // 1. Ringkasan Properti & Kamar
        $totalProperties = Property::count();
        $totalRooms = Room::whereHas('property')->count();
        $kamarTerisi = Room::whereHas('property')->where('status', 'terisi')->count();
        $kamarTersedia = Room::whereHas('property')->where('status', 'tersedia')->count();

        // 2. Ringkasan Kontrak & Keuangan
        $kontrakAktif = Contract::where('status', 'aktif')->count();
        
        $pendapatanBulanIni = Invoice::where('status', 'lunas')
            ->whereYear('updated_at', now()->year)
            ->whereMonth('updated_at', now()->month)
            ->sum('jumlah');

        $tagihanBelumBayar = Invoice::where('status', 'belum_bayar')->sum('jumlah');

        // 3. Ringkasan Tiket Perbaikan Aktif
        $tiketAktif = MaintenanceTicket::whereIn('status', ['diajukan', 'diproses'])->count();

        // 4. Data Widget Aktivitas Terbaru
        $tiketTerbaru = MaintenanceTicket::with(['room', 'pelapor.user'])
            ->latest('tanggal_lapor')
            ->take(5)
            ->get();

        $tagihanTerbaru = Invoice::with(['contract.room', 'contract.clientUser.user'])
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
            'tagihanBelumBayar',
            'tiketAktif',
            'tiketTerbaru',
            'tagihanTerbaru'
        ));
    }
}