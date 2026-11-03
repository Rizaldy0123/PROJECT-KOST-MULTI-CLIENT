@extends('layouts.app')

@section('title', 'Tambah Unit Kamar - KostWangi')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Unit Kamar Baru</h2>

    <form action="{{ route('admin.rooms.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Pilih Properti Kost</label>
            <select name="property_id" required class="w-full mt-1 p-2 border @error('property_id') border-red-500 @enderror rounded-md">
                <option value="">-- Pilih Properti --</option>
                @foreach($properties as $prop)
                    <option value="{{ $prop->id }}" {{ old('property_id') == $prop->id ? 'selected' : '' }}>{{ $prop->nama_properti }}</option>
                @endforeach
            </select>
            @error('property_id')
                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Tipe Kamar</label>
                <select name="tipe_kamar_id" required class="w-full mt-1 p-2 border @error('tipe_kamar_id') border-red-500 @enderror rounded-md">
                    <option value="">-- Pilih Tipe --</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}" {{ old('tipe_kamar_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->nama_tipe }} (Rp {{ number_format($type->harga_default, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @error('tipe_kamar_id')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Kamar</label>
                <input type="text" name="nomor_kamar" value="{{ old('nomor_kamar') }}" placeholder="Contoh: 101" required class="w-full mt-1 p-2 border @error('nomor_kamar') border-red-500 @enderror rounded-md">
                @error('nomor_kamar')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Lantai</label>
                <input type="number" name="lantai" value="{{ old('lantai', 1) }}" required class="w-full mt-1 p-2 border @error('lantai') border-red-500 @enderror rounded-md">
                @error('lantai')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Harga Sewa Bulanan (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga') }}" required class="w-full mt-1 p-2 border @error('harga') border-red-500 @enderror rounded-md">
                @error('harga')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status Kamar</label>
                <select name="status" class="w-full mt-1 p-2 border @error('status') border-red-500 @enderror rounded-md">
                    <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="terisi" {{ old('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                    <option value="perbaikan" {{ old('status') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                </select>
                @error('status')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan Kamar</button>
        </div>
    </form>
</div>
@endsection