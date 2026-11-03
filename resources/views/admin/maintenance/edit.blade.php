@extends('layouts.app')

@section('title', 'Edit Tiket Perbaikan - KostWangi')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Tiket Perbaikan</h2>

    <form action="{{ route('admin.maintenance.update', $maintenance?->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Kamar</label>
                <select name="room_id" required class="w-full mt-1 p-2 border rounded-md">
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ ($maintenance?->room_id == $room->id) ? 'selected' : '' }}>
                            Kamar {{ $room->nomor_kamar }} ({{ optional($room->property)->nama_properti ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pelapor (Penghuni)</label>
                <select name="pelapor_id" required class="w-full mt-1 p-2 border rounded-md">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ ($maintenance?->pelapor_id == $user->id) ? 'selected' : '' }}>
                            {{ $user->user->name ?? $user->user->nama ?? 'Tanpa Nama' }} ({{ $user->user->email ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Judul Masalah</label>
            <input type="text" name="judul" value="{{ old('judul', $maintenance?->judul) }}" required class="w-full mt-1 p-2 border rounded-md">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Detail Kerusakan / Deskripsi</label>
            <textarea name="deskripsi" rows="3" required class="w-full mt-1 p-2 border rounded-md">{{ old('deskripsi', $maintenance?->deskripsi) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status Penanganan</label>
            <select name="status" class="w-full mt-1 p-2 border rounded-md">
                <option value="diajukan" {{ ($maintenance?->status == 'diajukan') ? 'selected' : '' }}>Diajukan</option>
                <option value="diproses" {{ ($maintenance?->status == 'diproses') ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ ($maintenance?->status == 'selesai') ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ ($maintenance?->status == 'ditolak') ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.maintenance.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Perbarui Laporan</button>
        </div>
    </form>
</div>
@endsection