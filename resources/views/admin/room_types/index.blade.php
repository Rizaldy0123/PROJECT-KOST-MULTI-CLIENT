@extends('layouts.app')

@section('title', 'Tipe Kamar - KostWangi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Tipe Kamar</h1>
        <p class="text-sm text-gray-500">Kelola kategori kamar (Deluxe, Standard, VIP, dll.).</p>
    </div>
    <a href="{{ route('admin.room-types.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-medium">
        + Tambah Tipe Kamar
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
            <tr>
                <th class="p-4">Nama Tipe</th>
                <th class="p-4">Harga Default</th>
                <th class="p-4">Kapasitas</th>
                <th class="p-4">Jumlah Kamar</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roomTypes as $type)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 font-semibold text-gray-800">{{ $type->nama_tipe }}</td>
                    <td class="p-4">Rp {{ number_format($type->harga_default, 0, ',', '.') }} / bln</td>
                    <td class="p-4">{{ $type->kapasitas }} Orang</td>
                    <td class="p-4">{{ $type->rooms_count }} Unit</td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.room-types.edit', $type->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                        <form action="{{ route('admin.room-types.destroy', $type->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tipe kamar ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-400">Belum ada tipe kamar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection