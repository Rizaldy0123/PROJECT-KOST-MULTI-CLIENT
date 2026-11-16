<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientUser;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    // Menampilkan halaman daftar notifikasi
    public function index()
    {
        $clientId = session('active_client_id');

        $notifications = Notification::with('user')
            ->where('client_id', $clientId)
            ->latest()
            ->get();

        return view('admin.notifikasi.index', compact(
            'notifications'
        ));
    }

    // Menampilkan halaman form kirim notifikasi
    public function create()
    {
        $clientId = session('active_client_id');

        $penghuni = ClientUser::with('user')
            ->where('client_id', $clientId)
            ->where('role', 'penghuni')
            ->where('is_active', 1)
            ->get();

        return view('admin.notifikasi.create', compact(
            'penghuni'
        ));
    }

    // Menyimpan dan mengirim notifikasi
    public function store(Request $request)
    {
        $request->validate([
            'tujuan' => 'required|in:semua,tertentu',
            'user_id' => 'required_if:tujuan,tertentu|nullable|exists:users,id',
            'tipe_notifikasi' => 'required|in:pengumuman,tagihan,pembayaran,pemeliharaan,kontrak',
            'pesan' => 'required|string|max:500',
        ]);

        $clientId = session('active_client_id');

        $penghuni = ClientUser::where('client_id', $clientId)
            ->where('role', 'penghuni')
            ->where('is_active', 1);

        if ($request->tujuan === 'tertentu') {
            $penghuni->where('user_id', $request->user_id);
        }

        $penerima = $penghuni->get();

        DB::transaction(function () use ($penerima, $request, $clientId) {
            foreach ($penerima as $item) {
                Notification::create([
                    'client_id' => $clientId,
                    'user_id' => $item->user_id,
                    'tipe_notifikasi' => $request->tipe_notifikasi,
                    'pesan' => $request->pesan,
                    'status_baca' => 0,
                ]);
            }
        });

        return redirect()
            ->route('admin.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dikirim!');
    }
}
