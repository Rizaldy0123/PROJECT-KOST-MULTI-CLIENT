@extends('layouts.app')

@section('title', 'Dashboard Pengelola - KostWangi')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Pengelola Kost</h1>
    <p class="text-sm text-gray-500">Ringkasan statistik dan laporan operasional properti Anda.</p>
</div>

<!-- Kotak Statistik Utama (Grid 5 Kolom) -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <!-- Total Properti -->
    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-500 flex flex-col justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Properti</p>
            <h3 class="text-xl font-extrabold text-gray-800 mt-2">{{ $totalProperties }}</h3>
        </div>
        <a href="{{ route('admin.properties.index') }}" class="text-xs text-blue-600 hover:underline mt-4 inline-block">Kelola Properti &rarr;</a>
    </div>

    <!-- Status Kamar -->
    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-500 flex flex-col justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Kamar</p>
            <div class="mt-2 flex items-baseline space-x-1">
                <span class="text-lg font-extrabold text-green-600">{{ $kamarTerisi }}</span>
                <span class="text-xs text-gray-500">Terisi</span>
                <span class="text-gray-300 mx-1">/</span>
                <span class="text-lg font-extrabold text-gray-600">{{ $kamarTersedia }}</span>
                <span class="text-xs text-gray-500">Kosong</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Total Unit: {{ $totalRooms }}</p>
        </div>
        <a href="{{ route('admin.rooms.index') }}" class="text-xs text-green-600 hover:underline mt-2 inline-block">Cek Kamar &rarr;</a>
    </div>

    <!-- Pendapatan Bulan Ini -->
    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-indigo-500 flex flex-col justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pendapatan Bulan Ini</p>
            <h3 class="text-lg font-extrabold text-indigo-600 mt-2">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-400 mt-1">Kas Masuk</p>
        </div>
        <a href="{{ route('admin.payments.index') }}" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">Lihat Pembayaran &rarr;</a>
    </div>

    <!-- Total Piutang (Belum Lunas) -->
    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-red-500 flex flex-col justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Piutang</p>
            <h3 class="text-lg font-extrabold text-red-600 mt-2">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h3>
        </div>
        <a href="{{ route('admin.invoices.index') }}" class="text-xs text-red-600 hover:underline mt-4 inline-block">Cek Tagihan &rarr;</a>
    </div>

    <!-- Tiket Perbaikan Aktif -->
    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500 flex flex-col justify-between">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perbaikan Aktif</p>
            <h3 class="text-xl font-extrabold text-yellow-600 mt-2">{{ $tiketAktif }}</h3>
        </div>
        <a href="{{ route('admin.maintenance.index') }}" class="text-xs text-yellow-600 hover:underline mt-4 inline-block">Lihat Tiket &rarr;</a>
    </div>
</div>

<!-- Tabel Laporan Terbaru -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Laporan Kerusakan Terbaru -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Laporan Kerusakan Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
                    <tr>
                        <th class="p-3">Kamar</th>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tiketTerbaru as $maint)
                        <tr class="border-b hover:bg-gray-50 transition duration-150">
                            <td class="p-3">Kamar {{ $maint->room->nomor_kamar ?? '-' }}</td>
                            <td class="p-3 font-medium text-gray-800">
                                <!-- Menambahkan fallback variabel dan tautan klik -->
                                <a href="{{ route('admin.maintenance.index') }}" class="hover:text-blue-600 hover:underline">
                                    {{ $maint->judul ?? $maint->judul_laporan ?? $maint->keluhan ?? 'Lihat Detail Laporan' }}
                                </a>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">{{ ucfirst($maint->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-400">Belum ada laporan perbaikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tagihan & Sewa Terbaru -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Tagihan & Sewa Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
                    <tr>
                        <th class="p-3">No. Tagihan</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihanTerbaru as $inv)
                        <tr class="border-b hover:bg-gray-50 transition duration-150">
                            <td class="p-3 font-mono font-bold text-gray-800">
                                <!-- Menambahkan fallback variabel dan tautan klik -->
                                <a href="{{ route('admin.invoices.index') }}" class="hover:text-blue-600 hover:underline">
                                    {{ $inv->no_tagihan ?? $inv->nomor_tagihan ?? $inv->kode_tagihan ?? 'Lihat Detail Tagihan' }}
                                </a>
                            </td>
                            <td class="p-3 font-semibold text-gray-700">Rp {{ number_format($inv->jumlah, 0, ',', '.') }}</td>
                            <td class="p-3">
                                @if($inv->status == 'lunas')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Lunas</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-400">Belum ada data tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection