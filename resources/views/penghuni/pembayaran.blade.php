@extends('layouts.penghuni')
@section('title', 'Riwayat Pembayaran')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">Pembayaran yang pernah Anda lakukan.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($payments->count())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-3 px-4">No. Pembayaran</th>
                            <th class="py-3 px-4">Tagihan & Kamar</th>
                            <th class="py-3 px-4">Tanggal Bayar</th>
                            <th class="py-3 px-4">Metode</th>
                            <th class="py-3 px-4">Jumlah Bayar</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-sm">
                        @foreach($payments as $payment)
                            @php
                                $nominal = $payment->jumlah ?? $payment->jumlah_bayar ?? 0;
                                $bukti = $payment->bukti_pembayaran ?? $payment->bukti_transfer;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold text-gray-800">
                                    {{ $payment->nomor_pembayaran ?? 'PAY-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-3 px-4 text-gray-700">
                                    <span class="font-medium">{{ strtoupper($payment->invoice->jenis_tagihan ?? 'Sewa') }}</span>
                                    @if($payment->invoice?->contract?->room)
                                        <span class="text-xs text-gray-500 block">
                                            Kamar {{ $payment->invoice->contract->room->nomor_kamar }}
                                            {{ $payment->invoice->contract->room->property?->nama_properti ? '- ' . $payment->invoice->contract->room->property->nama_properti : '' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ $payment->metode_pembayaran }}
                                </td>
                                <td class="py-3 px-4 font-semibold text-green-600">
                                    Rp{{ number_format($nominal, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($payment->status === 'diverifikasi')
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                            Diverifikasi
                                        </span>
                                    @elseif($payment->status === 'ditolak')
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($bukti)
                                        <a href="{{ asset($bukti) }}"
                                           target="_blank"
                                           class="text-blue-600 hover:underline text-xs font-medium">
                                            Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Belum ada riwayat pembayaran.</p>
        @endif
    </div>
@endsection