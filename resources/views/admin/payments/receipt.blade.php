<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi Pembayaran - {{ $payment->nomor_pembayaran }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-8 print:bg-white print:p-0">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow print:shadow-none print:w-full">
        <!-- Header Kuitansi -->
        <div class="flex justify-between items-border border-b pb-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">KOSTWANGI</h1>
                <p class="text-xs text-gray-500">Platform Manajemen Properti & Kos</p>
            </div>
            <div class="text-right">
                <h2 class="text-lg font-semibold text-blue-600">KUITANSI PEMBAYARAN</h2>
                <p class="text-xs text-gray-500 font-mono">{{ $payment->nomor_pembayaran }}</p>
            </div>
        </div>

        <!-- Detail Informasi -->
        <div class="space-y-3 mb-6 text-sm">
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Tanggal Pembayaran</span>
                <span class="font-medium">{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d F Y') }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Nama Penghuni</span>
                <span class="font-medium">{{ $payment->invoice->contract->user->user->name ?? $payment->invoice->contract->user->user->nama ?? '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Properti & Kamar</span>
                <span class="font-medium">{{ $payment->invoice->contract->room->property->nama_properti ?? '-' }} (Kamar {{ $payment->invoice->contract->room->nomor_kamar ?? '-' }})</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">No. Tagihan Terkait</span>
                <span class="font-mono font-medium">{{ $payment->invoice->nomor_tagihan ?? '-' }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Metode Pembayaran</span>
                <span class="font-medium">{{ $payment->metode_pembayaran }}</span>
            </div>
            @if($payment->catatan)
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Catatan</span>
                <span class="font-medium">{{ $payment->catatan }}</span>
            </div>
            @endif
        </div>

        <!-- Total Bayar Box -->
        <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center mb-8 border">
            <span class="text-sm font-bold text-gray-700 uppercase">Total Pembayaran</span>
            <span class="text-xl font-extrabold text-green-600">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</span>
        </div>

        <!-- Tanda Tangan / Footer -->
        <div class="flex justify-between items-end mt-12 text-sm">
            <div class="text-xs text-gray-400">
                <p>Dokumen ini dicetak secara otomatis oleh sistem KostWangi.</p>
                <p>Tanggal cetak: {{ date('d/m/Y H:i') }}</p>
            </div>
            <div class="text-center">
                <p class="mb-16">Pengelola KostWangi</p>
                <p class="font-bold underline">( Admin )</p>
            </div>
        </div>

        <!-- Tombol Aksi Cetak (Disembunyikan saat diprint) -->
        <div class="mt-8 text-center print:hidden space-x-3">
            <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 font-medium">Cetak / Simpan PDF</button>
            <a href="{{ route('admin.payments.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 font-medium">Kembali</a>
        </div>
    </div>
</body>
</html>