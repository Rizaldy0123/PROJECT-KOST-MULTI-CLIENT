@extends('layouts.penghuni')

@section('title', 'Riwayat Pembayaran - KostWangi')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Riwayat Pembayaran</h2>
    <p class="text-gray-500">Pembayaran yang pernah Anda lakukan.</p>
</div>

<section class="bg-white rounded-xl shadow p-6">
    @forelse($payments as $payment)
        <div class="border-b py-3 flex justify-between gap-4">
            <div>
                <p class="font-semibold">{{ $payment->nomor_pembayaran }}</p>
                <p class="text-sm text-gray-500">{{ $payment->tanggal_bayar }}</p>
            </div>
            <p class="font-semibold text-green-600">
                Rp{{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
            </p>
        </div>
    @empty
        <p class="text-gray-500">Belum ada riwayat pembayaran.</p>
    @endforelse
</section>
@endsection
