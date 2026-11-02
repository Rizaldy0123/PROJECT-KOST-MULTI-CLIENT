@extends('layouts.app')

@section('title', 'Edit Kontrak Sewa - KostWangi')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Kontrak Sewa</h2>

    <form action="{{ route('admin.contracts.update', $contract->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Penghuni</label>
                <select name="client_user_id" required class="w-full mt-1 p-2 border rounded-md">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $contract->client_user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Kamar</label>
                <select name="room_id" required class="w-full mt-1 p-2 border rounded-md">
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $contract->room_id == $room->id ? 'selected' : '' }}>
                            Kamar {{ $room->nomor_kamar }} - {{ $room->property->nama_properti }} (Rp {{ number_format($room->harga, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai (Start Date)</label>
                <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date) }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai (End Date)</label>
                <input type="date" name="end_date" value="{{ old('end_date', $contract->end_date) }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status Kontrak</label>
            <select name="status" class="w-full mt-1 p-2 border rounded-md">
                <option value="aktif" {{ $contract->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="selesai" {{ $contract->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="dibatalkan" {{ $contract->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.contracts.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Perbarui Kontrak</button>
        </div>
    </form>
</div>
@endsection