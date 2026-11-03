@extends('layouts.app')

@section('title', 'Manajemen Tagihan - KostWangi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Tagihan & Keuangan</h1>
        <p class="text-sm text-gray-500">Kelola tagihan sewa dan status pembayaran penghuni.</p>
    </div>
    <a href="{{ route('admin.invoices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-medium">
        + Buat Tagihan Baru
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
            <tr>
                <th class="p-4">Penghuni & Kamar</th>
                <th class="p-4">Jenis Tagihan</th>
                <th class="p-4">Jumlah (Rp)</th>
                <th class="p-4">Jatuh Tempo</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">
                        <span class="font-bold text-gray-800">{{ $invoice->contract->user->name ?? '-' }}</span><br>
                        <span class="text-xs text-gray-400">Kamar {{ $invoice->contract->room->nomor_kamar ?? '-' }} - {{ $invoice->contract->room->property->nama_properti ?? '-' }}</span>
                    </td>
                    <td class="p-4 uppercase font-medium text-gray-700">{{ $invoice->jenis_tagihan }}</td>
                    <td class="p-4 font-semibold text-gray-800">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</td>
                    <td class="p-4 text-xs">{{ $invoice->tanggal_jatuh_tempo }}</td>
                    <td class="p-4">
                        @if($invoice->status == 'lunas')
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Lunas</span>
                        @elseif($invoice->status == 'belum_lunas')
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Belum Lunas</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Terlambat</span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                        <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tagihan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-400">Belum ada data tagihan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection