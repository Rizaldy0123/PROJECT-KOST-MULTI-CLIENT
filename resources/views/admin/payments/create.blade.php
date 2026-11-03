@extends('layouts.app')

@section('title', 'Catat Pembayaran - KostWangi')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Formulir Pencatatan Pembayaran</h2>

    <form action="{{ route('admin.payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Pilih Tagihan (Belum Lunas)</label>
            <select name="invoice_id" required class="w-full mt-1 p-2 border rounded-md">
                <option value="">-- Pilih Tagihan Penghuni --</option>
                @foreach($invoices as $invoice)
                    <option value="{{ $invoice->id }}">
                        {{ $invoice->nomor_tagihan }} - {{ $invoice->contract->user->user->name ?? 'Tanpa Nama' }} (Kamar {{ $invoice->contract->room->nomor_kamar ?? '-' }}) - Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah Bayar (Rp)</label>
                <input type="number" name="jumlah_bayar" required class="w-full mt-1 p-2 border rounded-md" placeholder="Contoh: 850000">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="w-full mt-1 p-2 border rounded-md">
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="Tunai">Tunai</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Unggah Bukti Transfer (Opsional)</label>
            <input type="file" name="bukti_transfer" accept="image/png, image/jpeg, image/jpg" class="w-full mt-1 p-2 border rounded-md text-sm">
            <span class="text-xs text-gray-400">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB.</span>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catatan / Keterangan</label>
            <textarea name="catatan" rows="3" class="w-full mt-1 p-2 border rounded-md" placeholder="Catatan tambahan (opsional)..."></textarea>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan Pembayaran</button>
        </div>
    </form>
</div>
@endsection