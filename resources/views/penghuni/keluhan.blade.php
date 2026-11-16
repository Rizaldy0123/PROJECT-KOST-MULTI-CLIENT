@extends('layouts.penghuni')

@section('title', 'Keluhan & Pemeliharaan - KostWangi')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Keluhan & Pemeliharaan</h1>
        <p class="text-sm text-gray-500 mt-1">Laporkan kerusakan fasilitas kamar atau area kost Anda kepada pengelola.</p>
    </div>

    {{-- Notifikasi Sukses / Error --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- FORMULIR PENGAJUAN KELUHAN --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 h-fit">
            <h2 class="text-lg font-bold text-gray-800 mb-1">Buat Laporan Baru</h2>
            @if ($contract && $contract->room)
                <p class="text-xs text-gray-500 mb-4">
                    Unit aktif: <span class="font-semibold text-gray-700">Kamar {{ $contract->room->nomor_kamar }}
                    {{ $contract->room->property?->nama_properti ? '- ' . $contract->room->property->nama_properti : '' }}</span>
                </p>

                <form action="{{ route('penghuni.keluhan.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Masalah</label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                            placeholder="Contoh: Kran air kamar mandi bocor"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('judul')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Detail Kerusakan</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" required
                            placeholder="Jelaskan kondisi kerusakan secara singkat..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg text-sm transition">
                        Kirim Laporan
                    </button>
                </form>
            @else
                <div class="bg-yellow-50 text-yellow-800 text-sm p-4 rounded-lg mt-3">
                    Anda belum memiliki kontrak sewa aktif sehingga belum dapat mengajukan keluhan.
                </div>
            @endif
        </div>

        {{-- DAFTAR RIWAYAT KELUHAN PENGHUNI --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Keluhan Saya</h2>

            @if ($tickets->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-xs uppercase text-gray-500">
                                <th class="py-3 px-4">Tanggal Lapor</th>
                                <th class="py-3 px-4">Judul & Detail</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-sm">
                            @foreach ($tickets as $ticket)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                        {{ $ticket->tanggal_lapor ? \Carbon\Carbon::parse($ticket->tanggal_lapor)->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <p class="font-semibold text-gray-800">{{ $ticket->judul }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $ticket->deskripsi }}</p>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        @php
                                            $status = strtolower($ticket->status ?? 'diajukan');
                                            $badge = match ($status) {
                                                'selesai' => 'bg-green-100 text-green-700',
                                                'diproses' => 'bg-blue-100 text-blue-700',
                                                'ditolak' => 'bg-red-100 text-red-700',
                                                default => 'bg-yellow-100 text-yellow-700',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $badge }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-sm">Belum ada laporan keluhan yang pernah Anda ajukan.</p>
            @endif
        </div>
    </div>
@endsection