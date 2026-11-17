@extends('layouts.penghuni')
@section('title', 'Tagihan Saya')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tagihan Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar rincian tagihan sewa kamar dan jatuh tempo pembayaran.</p>
    </div>

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

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @php
            $daftarTagihan = $invoices ?? ($contract ? $contract->invoices : collect());
        @endphp

        @if($daftarTagihan->count())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-3 px-4">Jenis Tagihan</th>
                            <th class="py-3 px-4">Jumlah</th>
                            <th class="py-3 px-4">Jatuh Tempo</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-sm">
                        @foreach($daftarTagihan as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 font-semibold text-gray-800">
                                    {{ strtoupper($invoice->jenis_tagihan) }}
                                </td>
                                <td class="py-3 px-4 text-gray-700">
                                    Rp{{ number_format($invoice->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($invoice->status === 'lunas')
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                            Lunas
                                        </span>
                                    @elseif($invoice->status === 'terlambat')
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                            Terlambat
                                        </span>
                                    @elseif($invoice->status === 'dibatalkan')
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                            Dibatalkan
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if(in_array($invoice->status, ['belum_bayar', 'terlambat']))
                                        <a href="{{ route('penghuni.tagihan.bayar', $invoice->id) }}"
                                            class="inline-flex items-center px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                            Bayar Sekarang
                                        </a>
                                    @elseif($invoice->status === 'lunas')
                                        <span class="text-xs text-green-600 font-medium">Sudah Dibayar</span>
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
            <p class="text-gray-500">Belum ada tagihan.</p>
        @endif
    </div>
@endsection