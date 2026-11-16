@extends('layouts.penghuni')

@section('title', 'Ubah Sewa - KostWangi')

@section('content')
@php $room = $contract->room; @endphp
<div class="mb-6">
    <a href="{{ route('penghuni.dashboard') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Ubah / Perpanjang Sewa</h2>
    <p class="text-gray-500">Kamar {{ $room->nomor_kamar }} · {{ $room->property->nama_properti ?? '-' }}</p>
</div>

<form action="{{ route('penghuni.sewa.update', $contract->id) }}" method="POST"
      class="max-w-xl bg-white rounded-xl shadow p-6">
    @csrf
    @method('PUT')

    <label class="block text-sm font-medium mb-1">Tanggal Mulai Sewa</label>
    <input type="date" name="start_date"
           value="{{ old('start_date', \Illuminate\Support\Carbon::parse($contract->start_date)->toDateString()) }}"
           class="w-full border rounded-lg p-3 mb-4" required>

    <label class="block text-sm font-medium mb-1">Durasi Sewa (bulan)</label>
    <input type="number" id="durasi" name="durasi_bulan" min="1" max="24"
           value="{{ old('durasi_bulan', $durasi) }}"
           class="w-full border rounded-lg p-3 mb-4" required>

    <div class="bg-gray-50 rounded-lg p-4 mb-5 text-sm">
        Estimasi total sewa:
        <span id="total" class="font-bold text-blue-600"></span>
    </div>

    <button type="submit" class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600">Simpan Perubahan</button>
    <a href="{{ route('penghuni.dashboard') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
</form>

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
