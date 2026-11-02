@extends('layouts.app')

@section('title', 'Data Kamar - KostWangi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Unit Kamar</h1>
        <p class="text-sm text-gray-500">Kelola unit kamar dan ketersediaannya.</p>
    </div>
    <a href="{{ route('admin.rooms.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-medium">
        + Tambah Unit Kamar
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
            <tr>
                <th class="p-4">Nomor Kamar</th>
                <th class="p-4">Properti</th>
                <th class="p-4">Tipe Kamar</th>
                <th class="p-4">Harga Bulanan</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $room)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 font-bold text-gray-800">Kamar {{ $room->nomor_kamar }}</td>
                    <td class="p-4">{{ $room->property->nama_properti ?? '-' }}</td>
                    <td class="p-4">{{ $room->roomType->nama_tipe ?? '-' }}</td>
                    <td class="p-4 font-semibold text-gray-700">Rp {{ number_format($room->harga, 0, ',', '.') }}</td>
                    <td class="p-4">
                        @if($room->status == 'tersedia')
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Tersedia</span>
                        @elseif($room->status == 'terisi')
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Terisi</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Perbaikan</span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.rooms.edit', $room->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                        <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus unit kamar ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-400">Belum ada unit kamar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection