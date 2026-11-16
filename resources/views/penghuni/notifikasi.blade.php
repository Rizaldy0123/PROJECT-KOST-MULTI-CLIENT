@extends('layouts.penghuni')

@section('title', 'Notifikasi - KostWangi')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Notifikasi</h2>
    <p class="text-gray-500">Informasi terbaru terkait kost Anda.</p>
</div>

<section class="bg-white rounded-xl shadow p-6 space-y-3">
    @forelse($notifications as $notification)
        @php
            // Sesuaikan pengecekan ini jika tipe kolom status_baca adalah string (misal: $notification->status_baca === 'sudah_dibaca')
            $sudahDibaca = (bool) $notification->status_baca;
        @endphp

        <div class="p-4 rounded-lg border flex items-start justify-between gap-4 {{ $sudahDibaca ? 'bg-gray-50 border-gray-200' : 'bg-blue-50/70 border-blue-200' }}">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $sudahDibaca ? 'bg-gray-200 text-gray-600' : 'bg-blue-600 text-white' }}">
                        {{ $sudahDibaca ? 'Sudah Dibaca' : 'Baru' }}
                    </span>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                        {{ $notification->tipe_notifikasi }}
                    </span>
                    <span class="text-xs text-gray-400">
                        • {{ $notification->created_at?->diffForHumans() }}
                    </span>
                </div>

                <p class="text-sm {{ $sudahDibaca ? 'text-gray-600' : 'text-gray-900 font-semibold' }}">
                    {{ $notification->pesan }}
                </p>
            </div>

            @if(!$sudahDibaca)
                <form action="{{ route('penghuni.notifikasi.baca', $notification->id) }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="text-xs font-medium text-blue-600 hover:text-blue-800 bg-white border border-blue-200 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition">
                        Tandai Dibaca
                    </button>
                </form>
            @endif
        </div>
    @empty
        <p class="text-gray-500">Belum ada notifikasi.</p>
    @endforelse
</section>
@endsection