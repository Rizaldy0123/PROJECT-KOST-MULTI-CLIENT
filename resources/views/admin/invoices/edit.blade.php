@extends('layouts.app')

@section('title', 'Edit Tagihan - KostWangi')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Tagihan</h2>

    <form action="{{ route('admin.invoices.update', $invoice->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Pilih Kontrak / Penghuni</label>
            <select name="room_assignment_id" required class="w-full mt-1 p-2 border rounded-md">
                @foreach($contracts as $contract)
                    <option value="{{ $contract->id }}" {{ $invoice->room_assignment_id == $contract->id ? 'selected' : '' }}>
                        {{ $contract->user->name ?? 'Penghuni' }} - Kamar {{ $contract->room->nomor_kamar ?? '' }} ({{ $contract->room->property->nama_properti ?? '' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Tagihan</label>
                <select name="jenis_tagihan" class="w-full mt-1 p-2 border rounded-md">
                    <option value="sewa" {{ $invoice->jenis_tagihan == 'sewa' ? 'selected' : '' }}>Sewa</option>
                    <option value="listrik" {{ $invoice->jenis_tagihan == 'listrik' ? 'selected' : '' }}>Listrik</option>
                    <option value="air" {{ $invoice->jenis_tagihan == 'air' ? 'selected' : '' }}>Air</option>
                    <option value="denda" {{ $invoice->jenis_tagihan == 'denda' ? 'selected' : '' }}>Denda</option>
                    <option value="lainnya" {{ $invoice->jenis_tagihan == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah Tagihan (Rp)</label>
                <input type="number" name="jumlah" value="{{ old('jumlah', $invoice->jumlah) }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Jatuh Tempo</label>
                <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $invoice->tanggal_jatuh_tempo) }}" required class="w-full mt-1 p-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                <select name="status" class="w-full mt-1 p-2 border rounded-md">
                    <option value="belum_bayar" {{ $invoice->status == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="lunas" {{ $invoice->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="terlambat" {{ $invoice->status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="dibatalkan" {{ $invoice->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Perbarui Tagihan</button>
        </div>
    </form>
</div>
@endsection