@extends('layouts.app')

@section('title', 'Edit Unit Kamar - KostWangi')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Unit Kamar</h2>

    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Pilih Properti Kost</label>
            <select name="property_id" required class="w-full mt-1 p-2 border rounded-md">
                @foreach($properties as $prop)
                    <option value="{{ $prop->id }}" {{ $room->property_id == $prop->id ? 'selected' : '' }}>{{ $prop->nama_properti }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Tipe Kamar</label>
                <select name="tipe_kamar_id" required class="w-full mt-1 p-2 border rounded-md">
                    @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}" {{ $room->tipe_kamar_id == $type->id ? 'selected' : '' }}>{{ $type->nama_tipe }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Kamar</label>
                <input type="text" name="nomor_kamar" value="{{ old('nomor_kamar', $room->nomor_kamar) }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Harga Sewa Bulanan (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $room->harga) }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status Kamar</label>
                <select name="status" class="w-full mt-1 p-2 border rounded-md">
                    <option value="tersedia" {{ $room->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="terisi" {{ $room->status == 'terisi' ? 'selected' : '' }}>Terisi</option>
                    <option value="perbaikan" {{ $room->status == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Perbarui Kamar</button>
        </div>
    </form>
</div>
@endsection