<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice.contract.room.property', 'invoice.contract.user.user']);

        // Fitur pencarian berdasarkan ID/kode pembayaran atau nama penghuni
        if ($request->filled('search')) {
            $search = $request->search;
            $numericId = preg_replace('/[^0-9]/', '', $search);

            $query->where(function ($q) use ($search, $numericId) {
                if ($numericId !== '') {
                    $q->where('id', (int) $numericId);
                }

                $q->orWhereHas('invoice.contract.user.user', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%");
                });
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
        // Ambil semua tagihan yang statusnya belum lunas
        $invoices = Invoice::where('status', '!=', 'lunas')
            ->with(['contract.room.property', 'contract.user.user'])
            ->orderBy('id', 'desc')
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

        $invoice = Invoice::with('contract')->findOrFail($validated['invoice_id']);

        // Handle upload bukti pembayaran jika ada
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payments'), $filename);
            $buktiPath = 'uploads/payments/' . $filename;
        }

        // Simpan sesuai kolom asli di tabel payments
        Payment::create([
            'invoice_id'        => $invoice->id,
            'client_user_id'    => $invoice->contract->client_user_id,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'jumlah'            => $validated['jumlah_bayar'],
            'bukti_pembayaran'  => $buktiPath,
            'status'            => 'diverifikasi',
            'tanggal_bayar'     => $validated['tanggal_bayar'],
        ]);

        // Update status tagihan menjadi lunas otomatis
        $invoice->update(['status' => 'lunas']);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dicatat dan tagihan telah dilunasi!');
    }

    public function destroy(Payment $payment)
    {
        // Hapus file bukti pembayaran jika ada
        if ($payment->bukti_pembayaran && file_exists(public_path($payment->bukti_pembayaran))) {
            unlink(public_path($payment->bukti_pembayaran));
        }

        // Kembalikan status tagihan menjadi belum_lunas
        $payment->invoice()->update(['status' => 'belum_lunas']);

        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Data pembayaran berhasil dihapus dan status tagihan dikembalikan!');
    }
}