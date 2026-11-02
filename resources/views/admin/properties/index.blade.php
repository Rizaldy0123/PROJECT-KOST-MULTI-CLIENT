@extends('layouts.app')

@section('title', 'Daftar Properti - KostWangi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Properti Kost</h1>
        <p class="text-sm text-gray-500">Kelola daftar gedung dan lokasi kost Anda.</p>
    </div>
    <a href="{{ route('admin.properties.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-medium">
        + Tambah Properti
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
            <tr>
                <th class="p-4">Nama Properti</th>
                <th class="p-4">Alamat</th>
                <th class="p-4">Jumlah Kamar</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($properties as $property)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4 font-semibold text-gray-800">{{ $property->nama_properti }}</td>
                    <td class="p-4">{{ $property->alamat }}</td>
                    <td class="p-4">{{ $property->rooms_count }} Kamar</td>
                    <td class="p-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded {{ $property->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($property->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <a href="{{ route('admin.properties.edit', $property->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                        <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus properti ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-400">Belum ada properti yang ditambahkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection