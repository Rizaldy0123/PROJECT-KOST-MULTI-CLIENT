<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Contract;
use App\Models\Notification;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        // Menampilkan daftar tagihan beserta relasi kontrak, kamar, dan penghuni
        $invoices = Invoice::whereHas('contract.room.property')
            ->with(['contract.room.property', 'contract.user'])
            ->latest()
            ->get();

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        // Ambil data kontrak yang aktif untuk dijadikan acuan tagihan
        $contracts = Contract::where('status', 'aktif')
            ->with(['room.property', 'user'])
            ->get();

        return view('admin.invoices.create', compact('contracts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_assignment_id' => 'required|exists:contracts,id',
            'jenis_tagihan'      => 'required|in:sewa,listrik,air,denda,lainnya',
            'jumlah'             => 'required|numeric|min:0',
            'tanggal_jatuh_tempo'=> 'required|date',
            'status'             => 'required|in:belum_bayar,lunas,terlambat,dibatalkan',
        ]);

        // Ambil data kontrak beserta relasi user untuk mendapatkan client_id & user_id
        $contract = Contract::with('user')->findOrFail($validated['room_assignment_id']);
        $validated['client_id'] = $contract->client_id;

        $invoice = Invoice::create($validated);

        // Kirim notifikasi otomatis ke penghuni
        if ($contract->user && $contract->user->user_id) {
            $nominal = number_format($invoice->jumlah, 0, ',', '.');
            Notification::create([
                'client_id'       => $contract->client_id,
                'user_id'         => $contract->user->user_id,
                'tipe_notifikasi' => 'tagihan',
                'pesan'           => "Tagihan {$invoice->jenis_tagihan} sebesar Rp {$nominal} telah diterbitkan. Jatuh tempo: {$invoice->tanggal_jatuh_tempo}.",
                'status_baca'     => 0,
            ]);
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Tagihan berhasil dibuat!');
    }

    public function edit(Invoice $invoice)
    {
        $contracts = Contract::with(['room.property', 'user'])->get();

        return view('admin.invoices.edit', compact('invoice', 'contracts'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'room_assignment_id' => 'required|exists:contracts,id',
            'jenis_tagihan'      => 'required|in:sewa,listrik,air,denda,lainnya',
            'jumlah'             => 'required|numeric|min:0',
            'tanggal_jatuh_tempo'=> 'required|date',
            'status'             => 'required|in:belum_bayar,lunas,terlambat,dibatalkan',
        ]);

        // Ambil data kontrak beserta relasi user untuk mendapatkan client_id & user_id
        $contract = Contract::with('user')->findOrFail($validated['room_assignment_id']);
        $validated['client_id'] = $contract->client_id;

        $invoice->update($validated);

        // Kirim notifikasi pembaruan tagihan ke penghuni
        if ($contract->user && $contract->user->user_id) {
            $nominal = number_format($invoice->jumlah, 0, ',', '.');
            Notification::create([
                'client_id'       => $contract->client_id,
                'user_id'         => $contract->user->user_id,
                'tipe_notifikasi' => 'tagihan',
                'pesan'           => "Tagihan {$invoice->jenis_tagihan} (Rp {$nominal}) diperbarui dengan status: {$invoice->status}.",
                'status_baca'     => 0,
            ]);
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Data tagihan berhasil diperbarui!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Tagihan berhasil dihapus!');
    }
}