@extends('layouts.app')

@section('title', 'Buat Tagihan - KostWangi')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Buat Tagihan Baru</h2>
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.invoices.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Kontrak / Penghuni</label>
                <select name="room_assignment_id" required class="w-full mt-1 p-2 border rounded-md">
                    <option value="">-- Pilih Kontrak Aktif --</option>
                    @foreach ($contracts as $contract)
                        <option value="{{ $contract->id }}">
                            {{ $contract->user->nama ?? 'Penghuni' }}- Kamar {{ $contract->room->nomor_kamar ?? '' }}
                            ({{ $contract->room->property->nama_properti ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Tagihan</label>
                    <select name="jenis_tagihan" class="w-full mt-1 p-2 border rounded-md">
                        <option value="sewa">Sewa</option>
                        <option value="listrik">Listrik</option>
                        <option value="air">Air</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah Tagihan (Rp)</label>
                    <input type="number" name="jumlah" required class="w-full mt-1 p-2 border rounded-md"
                        placeholder="Contoh: 750000">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Jatuh Tempo</label>
                    <input type="date" name="tanggal_jatuh_tempo" required class="w-full mt-1 p-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                    <select name="status" class="w-full mt-1 p-2 border rounded-md">
                        <option value="belum_bayar">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                        <option value="terlambat">Terlambat</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 border text-gray-600 rounded-md">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan Tagihan</button>
            </div>
        </form>
    </div>
@endsection
