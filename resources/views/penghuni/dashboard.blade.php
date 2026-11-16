@extends('layouts.penghuni')

@section('title', 'Informasi & Sewa Kost - KostWangi')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Informasi & Sewa Kost</h2>
    <p class="text-gray-500">Lihat kost yang tersedia, sewa kamar, dan kelola sewa Anda.</p>
</div>

{{-- ===== SEWA SAYA (aktif) ===== --}}
<section class="bg-white rounded-xl shadow p-6 mb-8">
    <h3 class="text-lg font-bold mb-4">Sewa Saya</h3>

    @if($activeContract && $activeContract->room)
        @php $room = $activeContract->room; @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <div>
                <p class="text-sm text-gray-500">Kost</p>
                <p class="font-bold">{{ $room->property->nama_properti ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Nomor Kamar</p>
                <p class="text-xl font-bold">{{ $room->nomor_kamar }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Harga Sewa / bulan</p>
                <p class="text-xl font-bold text-blue-600">Rp{{ number_format($room->harga, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tipe Kamar</p>
                <p class="font-semibold">{{ $room->roomType->nama_tipe ?? '-' }}
                    @if($room->roomType) <span class="text-gray-500 font-normal">({{ $room->roomType->kapasitas }} orang)</span> @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Mulai Sewa</p>
                <p class="font-semibold">{{ \Illuminate\Support\Carbon::parse($activeContract->start_date)->translatedFormat('d M Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Akhir Sewa</p>
                <p class="font-semibold">{{ \Illuminate\Support\Carbon::parse($activeContract->end_date)->translatedFormat('d M Y') }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-500">Alamat</p>
                <p class="font-semibold">{{ $room->property->alamat ?? '-' }}</p>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <a href="{{ route('penghuni.sewa.edit', $activeContract->id) }}"
               class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">Ubah / Perpanjang</a>

            <form action="{{ route('penghuni.sewa.destroy', $activeContract->id) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin membatalkan sewa kamar ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600">Batalkan Sewa</button>
            </form>
        </div>
    @else
        <p class="text-gray-500">Anda belum memiliki sewa aktif. Pilih kamar di bawah untuk mulai menyewa.</p>
    @endif
</section>

{{-- ===== KOST TERSEDIA ===== --}}
<section class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h3 class="text-lg font-bold">Kost Tersedia</h3>

        <form method="GET" action="{{ route('penghuni.dashboard') }}" class="flex gap-2">
            <select name="property_id" class="border rounded-lg px-3 py-2 text-sm bg-white" onchange="this.form.submit()">
                <option value="">Semua Kost</option>
                @foreach($properties as $property)
                    <option value="{{ $property->id }}" @selected(request('property_id') == $property->id)>
                        {{ $property->nama_properti }}
                    </option>
                @endforeach
            </select>
            @if(request('property_id'))
                <a href="{{ route('penghuni.dashboard') }}" class="px-3 py-2 text-sm text-gray-600 hover:underline">Reset</a>
            @endif
        </form>
    </div>

    @if($activeContract)
        <div class="bg-blue-50 text-blue-700 text-sm px-4 py-3 rounded-lg mb-4">
            Anda sudah memiliki sewa aktif. Batalkan sewa saat ini jika ingin menyewa kamar lain.
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($rooms as $room)
            <div class="bg-white rounded-xl shadow overflow-hidden flex flex-col">
                <div class="h-36 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-600 font-bold text-3xl">
                    {{ $room->nomor_kamar }}
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <p class="text-xs uppercase tracking-wide text-gray-400">{{ $room->property->nama_properti ?? '-' }}</p>
                    <h4 class="text-lg font-bold mb-1">Kamar {{ $room->nomor_kamar }}
                        <span class="text-sm font-normal text-gray-500">· {{ $room->roomType->nama_tipe ?? '-' }}</span>
                    </h4>
                    <p class="text-sm text-gray-500 mb-2">{{ $room->property->alamat ?? '' }}</p>
                    @if($room->roomType)
                        <p class="text-sm text-gray-600 mb-2">Kapasitas {{ $room->roomType->kapasitas }} orang</p>
                        @if($room->roomType->deskripsi)
                            <p class="text-sm text-gray-500 mb-3">{{ \Illuminate\Support\Str::limit($room->roomType->deskripsi, 90) }}</p>
                        @endif
                    @endif

                    <div class="mt-auto flex items-center justify-between pt-3">
                        <p class="text-lg font-bold text-blue-600">
                            Rp{{ number_format($room->harga, 0, ',', '.') }}<span class="text-xs text-gray-500 font-normal">/bln</span>
                        </p>

                        @if($activeContract)
                            <span class="px-4 py-2 rounded-lg text-sm bg-gray-200 text-gray-500 cursor-not-allowed">Sewa</span>
                        @else
                            <a href="{{ route('penghuni.sewa.create', ['room' => $room->id]) }}"
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Sewa</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow p-8 text-center text-gray-500">
                Belum ada kamar yang tersedia saat ini.
            </div>
        @endforelse
    </div>
</section>

{{-- ===== RIWAYAT SEWA ===== --}}
<section class="bg-white rounded-xl shadow p-6">
    <h3 class="text-lg font-bold mb-4">Riwayat Sewa</h3>

    @if($contracts->count())
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Kost</th>
                        <th class="p-3">Kamar</th>
                        <th class="p-3">Mulai</th>
                        <th class="p-3">Berakhir</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contracts as $contract)
                        <tr class="border-b">
                            <td class="p-3">{{ $contract->room->property->nama_properti ?? '-' }}</td>
                            <td class="p-3">{{ $contract->room->nomor_kamar ?? '-' }}</td>
                            <td class="p-3">{{ \Illuminate\Support\Carbon::parse($contract->start_date)->translatedFormat('d M Y') }}</td>
                            <td class="p-3">{{ \Illuminate\Support\Carbon::parse($contract->end_date)->translatedFormat('d M Y') }}</td>
                            <td class="p-3">
                                @php
                                    $badge = match($contract->status) {
                                        'aktif' => 'bg-green-100 text-green-800',
                                        'selesai' => 'bg-gray-100 text-gray-700',
                                        default => 'bg-red-100 text-red-700',
                                    };
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $badge }}">{{ ucfirst($contract->status) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">Belum ada riwayat sewa.</p>
    @endif
</section>
@endsection
