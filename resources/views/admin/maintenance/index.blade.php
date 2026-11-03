@extends('layouts.app')

@section('title', 'Tiket Perbaikan - KostWangi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Tiket Perbaikan</h1>
        <p class="text-sm text-gray-500">Kelola laporan kerusakan dan fasilitas dari penghuni.</p>
    </div>
    <a href="{{ route('admin.maintenance.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-medium">
        + Buat Tiket Baru
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
            <tr>
                <th class="p-4">Tanggal Lapor</th>
                <th class="p-4">Pelapor & Kamar</th>
                <th class="p-4">Judul Masalah</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($ticket->tanggal_lapor)->format('d/m/Y H:i') }}</td>
                    <td class="p-4">
                        <span class="font-bold text-gray-800">{{ $ticket->pelapor->name ?? 'Tidak diketahui' }}</span><br>
                        <span class="text-xs text-gray-400">Kamar {{ $ticket->room->nomor_kamar ?? '-' }} - {{ $ticket->room->property->nama_properti ?? '' }}</span>
                    </td>
                    <td class="p-4">
                        <span class="font-semibold text-gray-700">{{ $ticket->judul }}</span><br>
                        <span class="text-xs text-gray-500 truncate block max-w-xs">{{ $ticket->deskripsi }}</span>
                    </td>
                    <td class="p-4">
                        @if($ticket->status == 'diajukan')
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Diajukan</span>
                        @elseif($ticket->status == 'diproses')
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Diproses</span>
                        @elseif($ticket->status == 'selesai')
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Selesai</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Ditolak</span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.maintenance.edit', $ticket->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                        <form action="{{ route('admin.maintenance.destroy', $ticket->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus laporan perbaikan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-400">Belum ada data laporan perbaikan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection