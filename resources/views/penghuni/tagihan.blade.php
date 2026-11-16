@extends('layouts.penghuni')
@section('title', 'Tagihan Saya')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Tagihan Saya</h2>
        <p class="text-gray-500">Daftar rincian tagihan sewa kamar dan jatuh tempo pembayaran.</p>
    </div>

    <section class="bg-white rounded-xl shadow p-6 mb-8">
        <h3 class="text-lg font-bold mb-4">Daftar Tagihan</h3>

        @php
            $daftarTagihan = $invoices ?? ($contract ? $contract->invoices : collect());
        @endphp

        @if($daftarTagihan->count())
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Jenis Tagihan</th>
                            <th class="p-3">Jumlah</th>
                            <th class="p-3">Jatuh Tempo</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarTagihan as $invoice)
                            <tr class="border-b">
                                <td class="p-3">{{ $invoice->jenis_tagihan }}</td>
                                <td class="p-3">
                                    Rp{{ number_format($invoice->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="p-3">{{ $invoice->tanggal_jatuh_tempo }}</td>
                                <td class="p-3">{{ ucfirst($invoice->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Belum ada tagihan.</p>
        @endif
    </section>
@endsection