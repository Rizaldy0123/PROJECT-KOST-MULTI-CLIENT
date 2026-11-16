@extends('layouts.penghuni')

@section('title', 'Sewa Kamar - KostWangi')

@section('content')
<div class="mb-6">
    <a href="{{ route('penghuni.dashboard') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Sewa Kamar {{ $room->nomor_kamar }}</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-xs uppercase tracking-wide text-gray-400">{{ $room->property->nama_properti ?? '-' }}</p>
        <h3 class="text-lg font-bold">Kamar {{ $room->nomor_kamar }}</h3>
        <p class="text-sm text-gray-500 mb-3">{{ $room->property->alamat ?? '' }}</p>
        <p class="text-sm">Tipe: <span class="font-semibold">{{ $room->roomType->nama_tipe ?? '-' }}</span></p>
        @if($room->roomType)
            <p class="text-sm">Kapasitas: <span class="font-semibold">{{ $room->roomType->kapasitas }} orang</span></p>
        @endif
        <p class="text-xl font-bold text-blue-600 mt-3">Rp{{ number_format($room->harga, 0, ',', '.') }}<span class="text-xs text-gray-500 font-normal">/bulan</span></p>
    </div>

    <form action="{{ route('penghuni.sewa.store') }}" method="POST" class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">

        <label class="block text-sm font-medium mb-1">Tanggal Mulai Sewa</label>
        <input type="date" name="start_date" min="{{ now()->toDateString() }}"
               value="{{ old('start_date', now()->toDateString()) }}"
               class="w-full border rounded-lg p-3 mb-4" required>

        <label class="block text-sm font-medium mb-1">Durasi Sewa (bulan)</label>
        <input type="number" id="durasi" name="durasi_bulan" min="1" max="24"
               value="{{ old('durasi_bulan', 1) }}"
               class="w-full border rounded-lg p-3 mb-4" required>

        <div class="bg-gray-50 rounded-lg p-4 mb-5 text-sm">
            Estimasi total sewa:
            <span id="total" class="font-bold text-blue-600"></span>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">Konfirmasi Sewa</button>
        <a href="{{ route('penghuni.dashboard') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
    </form>
</div>

<script>
    const harga = {{ (int) $room->harga }};
    const durasi = document.getElementById('durasi');
    const total = document.getElementById('total');
    function hitung() {
        const n = Math.max(1, parseInt(durasi.value) || 1);
        total.textContent = 'Rp' + (harga * n).toLocaleString('id-ID');
    }
    durasi.addEventListener('input', hitung);
    hitung();
</script>
@endsection
