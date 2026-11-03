@extends('layouts.app')

@section('title', 'Manajemen Pembayaran - KostWangi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Pembayaran</h1>
        <p class="text-sm text-gray-500">Kelola dan pantau riwayat transaksi pembayaran sewa kos.</p>
    </div>
    <a href="{{ route('admin.payments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-medium">
        + Catat Pembayaran Baru
    </a>
</div>

<!-- Form Filter / Pencarian -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form action="{{ route('admin.payments.index') }}" method="GET" class="flex gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Pembayaran atau Nama Penghuni..." class="flex-1 p-2 border rounded-md text-sm">
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.payments.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm flex items-center">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
            <tr>
                <th class="p-4">No. Pembayaran</th>
                <th class="p-4">No. Tagihan & Penghuni</th>
                <th class="p-4">Tanggal Bayar</th>
                <th class="p-4">Jumlah Bayar</th>
                <th class="p-4">Metode</th>
                <th class="p-4">Bukti</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 font-mono font-bold text-gray-800">{{ $payment->nomor_pembayaran }}</td>
                    <td class="p-4">
                        <span class="font-bold text-gray-800">{{ $payment->invoice->nomor_tagihan ?? '-' }}</span><br>
                        <span class="text-xs text-gray-500">{{ $payment->invoice->contract->user->user->name ?? $payment->invoice->contract->user->user->nama ?? '-' }} (Kamar {{ $payment->invoice->contract->room->nomor_kamar ?? '-' }})</span>
                    </td>
                    <td class="p-4 text-xs">{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d/m/Y') }}</td>
                    <td class="p-4 font-semibold text-green-600">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="p-4">{{ $payment->metode_pembayaran }}</td>
                    <td class="p-4">
                        @if($payment->bukti_transfer)
                            <a href="{{ asset($payment->bukti_transfer) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-medium">Lihat Bukti</a>
                        @else
                            <span class="text-xs text-gray-400">Tidak ada</span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.payments.receipt', $payment->id) }}" target="_blank" class="text-green-600 hover:underline font-medium">Kuitansi</a>
                        <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data pembayaran ini? Status tagihan akan dikembalikan menjadi belum lunas.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-400">Tidak ada data pembayaran ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection