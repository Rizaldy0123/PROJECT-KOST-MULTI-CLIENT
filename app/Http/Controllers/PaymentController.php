<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.contract.room.property', 'invoice.contract.user.user']);

        // Fitur pencarian berdasarkan nomor pembayaran atau nama penghuni
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nomor_pembayaran', 'like', "%{$search}%")
                ->orWhereHas('invoice.contract.user.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                });
        }

        $payments = $query->orderBy('id', 'desc')->get();

        return view('admin.payments.index', compact('payments'));
    }

        public function receipt(Payment $payment)
    {
        // Memuat relasi yang dibutuhkan untuk kuitansi
        $payment->load(['invoice.contract.room.property', 'invoice.contract.user.user']);
        
        return view('admin.payments.receipt', compact('payment'));
    }   

    public function create()
    {
        // Ambil tagihan yang belum lunas saja untuk dibayarkan
        $invoices = Invoice::where('status', 'belum_lunas')
            ->with(['contract.room.property', 'contract.user.user'])
            ->get();

        return view('admin.payments.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id'        => 'required|exists:invoices,id',
            'tanggal_bayar'     => 'required|date',
            'jumlah_bayar'      => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:50',
            'bukti_transfer'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan'           => 'nullable|string|max:500',
        ]);

        $invoice = Invoice::with('contract.room.property')->findOrFail($validated['invoice_id']);
        $validated['client_id'] = $invoice->contract->room->property->client_id ?? 1;

        // Generate nomor pembayaran otomatis
        $validated['nomor_pembayaran'] = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        // Handle upload bukti transfer jika ada
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payments'), $filename);
            $validated['bukti_transfer'] = 'uploads/payments/' . $filename;
        }

        Payment::create($validated);

        // Update status tagihan menjadi lunas otomatis
        $invoice->update(['status' => 'lunas']);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dicatat dan tagihan telah dilunasi!');
    }

    public function destroy(Payment $payment)
    {
        // Hapus file bukti transfer jika ada
        if ($payment->bukti_transfer && file_exists(public_path($payment->bukti_transfer))) {
            unlink(public_path($payment->bukti_transfer));
        }

        // Kembalikan status tagihan menjadi belum_lunas
        $payment->invoice()->update(['status' => 'belum_lunas']);

        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Data pembayaran berhasil dihapus dan status tagihan dikembalikan!');
    }
}