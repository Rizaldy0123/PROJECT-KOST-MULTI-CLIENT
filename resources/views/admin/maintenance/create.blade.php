@extends('layouts.app')

@section('title', 'Buat Tiket Perbaikan - KostWangi')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Buat Tiket Perbaikan Baru</h2>

    <form action="{{ route('admin.maintenance.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Kamar</label>
                <select name="room_id" required class="w-full mt-1 p-2 border rounded-md">
                    <option value="">-- Pilih Kamar --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">Kamar {{ $room->nomor_kamar }} ({{ $room->property->nama_properti ?? '' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pelapor (Penghuni)</label>
                <select name="pelapor_id" required class="w-full mt-1 p-2 border rounded-md">
                    <option value="">-- Pilih Pelapor --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Judul Masalah</label>
            <input type="text" name="judul" required class="w-full mt-1 p-2 border rounded-md" placeholder="Contoh: Kran Air Kamar Mandi Bocor">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Detail Kerusakan / Deskripsi</label>
            <textarea name="deskripsi" rows="3" required class="w-full mt-1 p-2 border rounded-md" placeholder="Jelaskan detail kerusakan yang terjadi..."></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status Awal</label>
            <select name="status" class="w-full mt-1 p-2 border rounded-md">
                <option value="diajukan">Diajukan</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.maintenance.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan Laporan</button>
        </div>
    </form>
</div>
@endsection