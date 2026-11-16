@extends('layouts.penghuni')

@section('title', 'Notifikasi - KostWangi')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Notifikasi</h2>
    <p class="text-gray-500">Informasi terbaru terkait kost Anda.</p>
</div>

<section class="bg-white rounded-xl shadow p-6">
    @forelse($notifications as $notification)
        <div class="border-b py-3">
            <p>{{ $notification->pesan }}</p>
            <p class="text-sm text-gray-500">{{ $notification->tipe_notifikasi }}</p>
        </div>
    @empty
        <p class="text-gray-500">Belum ada notifikasi.</p>
    @endforelse
</section>
@endsection
