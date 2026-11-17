<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use App\Traits\ResolvesPenghuni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @method \App\Models\ClientUser currentPenghuni()
 */
class PenghuniPembayaranController extends Controller
{
    use ResolvesPenghuni;

    // Halaman Form Pembayaran Tagihan oleh Penghuni
    public function create(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        if ($invoice->status === 'lunas') {
            return redirect()->route('penghuni.tagihan')
                ->with('error', 'Tagihan ini sudah berstatus lunas.');
        }

        if ($invoice->status === 'dibatalkan') {
            return redirect()->route('penghuni.tagihan')
                ->with('error', 'Tagihan yang dibatalkan tidak dapat dibayar.');
        }

        $invoice->load(['contract.room.property', 'contract.room.roomType']);

        return view('penghuni.bayar', [
            'user'    => Auth::user(),
            'invoice' => $invoice,
        ]);
    }

    // Proses Simpan Pembayaran Penghuni
    public function store(Request $request, Invoice $invoice)
    {
        $clientUser = $this->authorizeInvoice($invoice);

        if ($invoice->status === 'lunas' || $invoice->status === 'dibatalkan') {
            return redirect()->route('penghuni.tagihan')
                ->with('error', 'Tagihan ini tidak dapat diproses lagi.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => 'required|string|max:50',
            'bukti_pembayaran'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'metode_pembayaran.required' => 'Silakan pilih metode pembayaran.',
            'bukti_pembayaran.required'  => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image'     => 'File bukti harus berupa gambar (JPG/PNG).',
            'bukti_pembayaran.max'       => 'Ukuran file maksimal 2 MB.',
        ]);

        // Simpan file bukti pembayaran ke public/uploads/payments
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payments'), $filename);
            $buktiPath = 'uploads/payments/' . $filename;
        }

        DB::transaction(function () use ($invoice, $clientUser, $validated, $buktiPath) {
            Payment::create([
                'invoice_id'        => $invoice->id,
                'client_user_id'    => $clientUser->id,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'jumlah'            => $invoice->jumlah,
                'bukti_pembayaran'  => $buktiPath,
                'status'            => 'diverifikasi',
                'tanggal_bayar'     => now()->toDateString(),
            ]);

            $invoice->update(['status' => 'lunas']);

            if ($clientUser->user_id) {
                $nominal = number_format($invoice->jumlah, 0, ',', '.');
                Notification::create([
                    'client_id'       => $invoice->client_id,
                    'user_id'         => $clientUser->user_id,
                    'tipe_notifikasi' => 'pembayaran',
                    'pesan'           => "Pembayaran tagihan {$invoice->jenis_tagihan} sebesar Rp {$nominal} via {$validated['metode_pembayaran']} berhasil dicatat (LUNAS).",
                    'status_baca'     => 0,
                ]);
            }
        });

        return redirect()->route('penghuni.pembayaran')
            ->with('success', 'Pembayaran berhasil dikirim! Tagihan Anda telah dinyatakan lunas.');
    }

    // Pastikan tagihan benar-benar milik penghuni yang sedang login
    private function authorizeInvoice(Invoice $invoice)
    {
        $clientUser = $this->currentPenghuni();
        $invoice->loadMissing('contract');

        abort_unless(
            $invoice->contract && $invoice->contract->client_user_id === $clientUser->id,
            403
        );

        return $clientUser;
    }
}