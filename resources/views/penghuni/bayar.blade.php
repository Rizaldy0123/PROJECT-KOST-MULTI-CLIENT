@extends('layouts.penghuni')
@section('title', 'Bayar Tagihan')

@section('content')
    <div class="mb-6">
        <a href="{{ route('penghuni.tagihan') }}" class="text-sm text-blue-600 hover:underline">
            &larr; Kembali ke Tagihan Saya
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Pembayaran Tagihan</h1>
        <p class="text-sm text-gray-500 mt-1">Pilih metode pembayaran dan unggah bukti transfer Anda.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Ringkasan Tagihan --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 h-fit">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Rincian Tagihan</p>
            <h2 class="text-lg font-bold text-gray-800 mt-1">
                {{ $invoice->contract->room->property->nama_properti ?? 'Properti Kost' }}
            </h2>
            <p class="text-sm text-gray-600">
                Kamar {{ $invoice->contract->room->nomor_kamar ?? '-' }}
                ({{ $invoice->contract->room->roomType->nama_tipe ?? '-' }})
            </p>

            <div class="border-t my-4 pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Jenis Tagihan</span>
                    <span class="font-semibold text-gray-800 uppercase">{{ $invoice->jenis_tagihan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jatuh Tempo</span>
                    <span class="font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->format('d M Y') }}
                    </span>
                </div>
            </div>

            <div class="border-t pt-4">
                <p class="text-xs text-gray-500">Total yang Harus Dibayar</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">
                    Rp{{ number_format($invoice->jumlah, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Form Upload Bukti Pembayaran --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <form action="{{ route('penghuni.tagihan.bayar.store', $invoice->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="metode_pembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                        Metode Pembayaran
                    </label>
                    <select name="metode_pembayaran" id="metode_pembayaran" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        <option value="Transfer Bank BCA" {{ old('metode_pembayaran') === 'Transfer Bank BCA' ? 'selected' : '' }}>
                            Transfer Bank BCA (1234567890 a.n. Pengelola KostWangi)
                        </option>
                        <option value="Transfer Bank BRI" {{ old('metode_pembayaran') === 'Transfer Bank BRI' ? 'selected' : '' }}>
                            Transfer Bank BRI (0987654321 a.n. Pengelola KostWangi)
                        </option>
                        <option value="Transfer Bank Mandiri" {{ old('metode_pembayaran') === 'Transfer Bank Mandiri' ? 'selected' : '' }}>
                            Transfer Bank Mandiri (1122334455 a.n. Pengelola KostWangi)
                        </option>
                        <option value="QRIS" {{ old('metode_pembayaran') === 'QRIS' ? 'selected' : '' }}>
                            QRIS KostWangi
                        </option>
                        <option value="E-Wallet (DANA / GoPay / OVO)" {{ old('metode_pembayaran') === 'E-Wallet (DANA / GoPay / OVO)' ? 'selected' : '' }}>
                            E-Wallet (DANA / GoPay / OVO)
                        </option>
                    </select>
                </div>

                <div>
                    <label for="bukti_pembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                        Unggah Bukti Pembayaran (JPG / PNG, Maks. 2 MB)
                    </label>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/jpeg,image/png,image/jpg" required
                        class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg p-2">
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                        Kirim Pembayaran
                    </button>
                    <a href="{{ route('penghuni.tagihan') }}"
                        class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection