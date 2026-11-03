<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Contract;
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

        // Ambil data kontrak untuk mendapatkan client_id secara otomatis
        $contract = Contract::findOrFail($validated['room_assignment_id']);
        $validated['client_id'] = $contract->client_id;

        Invoice::create($validated);

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

        // Ambil data kontrak untuk mendapatkan client_id secara otomatis
        $contract = Contract::findOrFail($validated['room_assignment_id']);
        $validated['client_id'] = $contract->client_id;

        $invoice->update($validated);

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