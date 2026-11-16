
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Notifikasi
            </h1>
            <p class="text-gray-500 mt-1">
                Daftar notifikasi yang telah dikirim kepada penghuni.
            </p>
        </div>

        <a href="{{ route('admin.notifikasi.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center">
            + Buat Notifikasi
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($notifications->isEmpty())
            <div class="p-8 text-center text-gray-500">
                Belum ada notifikasi yang dikirim.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Penerima</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Pesan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $index => $notification)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $notification->user->name ?? $notification->user->nama ?? 'Penghuni' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ ucfirst($notification->tipe_notifikasi) }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $notification->pesan }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($notification->status_baca)
                                        <span class="text-green-600">Sudah dibaca</span>
                                    @else
                                        <span class="text-orange-600">Belum dibaca</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $notification->created_at?->format('d-m-Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
