@extends('layouts.app')

@section('title', 'Tambah Unit Kamar - KostWangi')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Unit Kamar Baru</h2>

    <form action="{{ route('admin.rooms.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Pilih Properti Kost</label>
            <select name="property_id" required class="w-full mt-1 p-2 border rounded-md">
                <option value="">-- Pilih Properti --</option>
                @foreach($properties as $prop)
                    <option value="{{ $prop->id }}">{{ $prop->nama_properti }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Tipe Kamar</label>
                <select name="tipe_kamar_id" required class="w-full mt-1 p-2 border rounded-md">
                    <option value="">-- Pilih Tipe --</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->nama_tipe }} (Rp {{ number_format($type->harga_default, 0, ',', '.') }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Kamar</label>
                <input type="text" name="nomor_kamar" value="{{ old('nomor_kamar') }}" placeholder="Contoh: 101" required class="w-full mt-1 p-2 border rounded-md">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Lantai</label>
                <input type="number" name="lantai" value="{{ old('lantai', 1) }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Harga Sewa Bulanan (Rp)</label>
                <input type="number" name="harga_bulanan" value="{{ old('harga_bulanan') }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status Kamar</label>
                <select name="status" class="w-full mt-1 p-2 border rounded-md">
                    <option value="tersedia">Tersedia</option>
                    <option value="terisi">Terisi</option>
                    <option value="perbaikan">Perbaikan</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan Kamar</button>
        </div>
    </form>
</div>
@endsection