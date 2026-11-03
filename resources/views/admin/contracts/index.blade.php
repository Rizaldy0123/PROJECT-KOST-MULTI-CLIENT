@extends('layouts.app')

@section('title', 'Manajemen Kontrak - KostWangi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Kontrak Sewa</h1>
        <p class="text-sm text-gray-500">Kelola masa sewa penghuni dan kamar.</p>
    </div>
    <a href="{{ route('admin.contracts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-medium">
        + Buat Kontrak Baru
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
            <tr>
                <th class="p-4">Penghuni</th>
                <th class="p-4">Kamar & Properti</th>
                <th class="p-4">Periode Sewa</th>
                <th class="p-4">Harga Kamar</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contracts as $contract)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 font-bold text-gray-800">{{ $contract->user->user->name ?? $contract->user->user->nama ?? '-' }}</td>
                    <td class="p-4">
                        Kamar {{ $contract->room->nomor_kamar ?? '-' }} <br>
                        <span class="text-xs text-gray-400">{{ $contract->room->property->nama_properti ?? '-' }}</span>
                    </td>
                    <td class="p-4 text-xs">
                        {{ $contract->start_date }} s/d <br> {{ $contract->end_date }}
                    </td>
                    <td class="p-4 font-semibold text-gray-700">Rp {{ number_format($contract->room->harga ?? 0, 0, ',', '.') }}</td>
                    <td class="p-4">
                        @if($contract->status == 'aktif')
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Aktif</span>
                        @elseif($contract->status == 'selesai')
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Selesai</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.contracts.edit', $contract->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                        <form action="{{ route('admin.contracts.destroy', $contract->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data kontrak ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-400">Belum ada data kontrak sewa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection