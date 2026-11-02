@extends('layouts.app')

@section('title', 'Tambah Tipe Kamar - KostWangi')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Tipe Kamar Baru</h2>

    <form action="{{ route('admin.room-types.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Tipe Kamar</label>
            <input type="text" name="nama_tipe" value="{{ old('nama_tipe') }}" placeholder="Contoh: Deluxe AC" required class="w-full mt-1 p-2 border rounded-md">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Harga Bulanan Default (Rp)</label>
                <input type="number" name="harga_default" value="{{ old('harga_default') }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Kapasitas (Orang)</label>
                <input type="number" name="kapasitas" value="{{ old('kapasitas', 1) }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi / Fasilitas</label>
            <textarea name="deskripsi" rows="3" class="w-full mt-1 p-2 border rounded-md" placeholder="AC, Kamar Mandi Dalam, Kasur Queen">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.room-types.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan Tipe</button>
        </div>
    </form>
</div>
@endsection