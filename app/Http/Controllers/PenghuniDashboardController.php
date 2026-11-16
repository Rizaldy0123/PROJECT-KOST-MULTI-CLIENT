<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\MaintenanceTicket;
use App\Models\Notification;
use App\Models\Property;
use App\Models\Room;
use App\Traits\ResolvesPenghuni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenghuniDashboardController extends Controller
{
    use ResolvesPenghuni;

    /**
     * Halaman utama: Informasi & Sewa Kost
     * - menampilkan sewa aktif + riwayat sewa milik penghuni
     * - menampilkan daftar kamar kost yang tersedia untuk disewa
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $clientUser = $this->currentPenghuni();

        // Semua kontrak milik penghuni (riwayat sewa)
        $contracts = Contract::where('client_user_id', $clientUser->id)
            ->with(['room.property', 'room.roomType'])
            ->latest('id')
            ->get();

        $activeContract = $contracts->firstWhere('status', 'aktif');

        // Filter properti (opsional)
        $properties = Property::where('status', 'aktif')->orderBy('nama_properti')->get();

        $rooms = Room::where('status', 'tersedia')
            ->whereHas('property', function ($q) use ($request) {
                $q->where('status', 'aktif');
                if ($request->filled('property_id')) {
                    $q->where('id', $request->property_id);
                }
            })
            ->with(['property', 'roomType'])
            ->orderBy('harga')
            ->get();

        return view('penghuni.dashboard', compact(
            'user',
            'clientUser',
            'contracts',
            'activeContract',
            'properties',
            'rooms'
        ));
    }

    public function tagihan()
    {
        $user = Auth::user();
        $clientUser = $this->currentPenghuni();

        $contract = $this->activeContract($clientUser);

        return view('penghuni.tagihan', compact('user', 'contract'));
    }

    public function pembayaran()
    {
        $user = Auth::user();
        $clientUser = $this->currentPenghuni();

        $contract = $this->activeContract($clientUser);

        $payments = $contract
            ? $contract->invoices->flatMap(fn ($invoice) => $invoice->payments)
            : collect();

        return view('penghuni.pembayaran', compact('user', 'payments'));
    }

    public function keluhan()
    {
        $user = Auth::user();
        $clientUser = $this->currentPenghuni();

        $contract = $this->activeContract($clientUser);

        $tickets = MaintenanceTicket::where('client_id', $clientUser->client_id)
            ->where('pelapor_id', $clientUser->id)
            ->latest('tanggal_lapor')
            ->get();

        return view('penghuni.keluhan', compact('user', 'contract', 'tickets'));
    }

    public function notifikasi()
    {
        $user = Auth::user();
        $clientUser = $this->currentPenghuni();

        $notifications = Notification::where('client_id', $clientUser->client_id)
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->get();

        return view('penghuni.notifikasi', compact('user', 'notifications'));
    }

    // Menyimpan keluhan penghuni
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:500',
        ]);

        $clientUser = $this->currentPenghuni();

        $contract = $clientUser->contracts()
            ->where('status', 'aktif')
            ->first();

        if (! $contract) {
            return redirect()
                ->route('penghuni.keluhan')
                ->with('error', 'Anda belum memiliki sewa aktif, sehingga belum bisa mengajukan keluhan.');
        }

        MaintenanceTicket::create([
            'client_id' => $clientUser->client_id,
            'room_id' => $contract->room_id,
            'pelapor_id' => $clientUser->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('penghuni.keluhan')
            ->with('success', 'Keluhan berhasil diajukan!');
    }

    private function activeContract($clientUser): ?Contract
    {
        return $clientUser->contracts()
            ->with(['room.roomType', 'invoices.payments'])
            ->where('status', 'aktif')
            ->latest('id')
            ->first();
    }
}
