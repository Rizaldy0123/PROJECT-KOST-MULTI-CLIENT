
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penghuni - KostWangi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-blue-700">
                KostWangi
            </h1>

            <div class="flex items-center gap-4">
                <span class="text-gray-700">
                    Halo, {{ $user->nama }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">

        <!-- Judul -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Dashboard Penghuni
            </h2>
            <p class="text-gray-500">
                Informasi kamar, sewa, tagihan, dan layanan kost Anda.
            </p>
        </div>

        <!-- Informasi Sewa -->
        <section class="bg-white rounded-xl shadow p-6 mb-8">
            <h3 class="text-lg font-bold mb-4">Informasi Kamar dan Sewa</h3>

            @if($contract && $contract->room)
                
<div class="grid grid-cols-1 md:grid-cols-4 gap-5">

    <div>
        <p class="text-sm text-gray-500">Nomor Kamar</p>
        <p class="text-xl font-bold">
            {{ $contract->room->nomor_kamar }}
        </p>
    </div>

    <div>
        <p class="text-sm text-gray-500">Harga Sewa</p>
        <p class="text-xl font-bold text-blue-600">
            Rp{{ number_format($contract->room->harga, 0, ',', '.') }}
        </p>
    </div>

    <div>
        <p class="text-sm text-gray-500">Mulai Sewa</p>
        <p class="font-semibold">{{ $contract->start_date }}</p>
    </div>

    <div>
        <p class="text-sm text-gray-500">Akhir Sewa</p>
        <p class="font-semibold">{{ $contract->end_date }}</p>
    </div>

</div>

@if($contract->room->roomType)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
        <div>
            <p class="text-sm text-gray-500">Tipe Kamar</p>
            <p class="font-semibold">
                {{ $contract->room->roomType->nama_tipe }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Kapasitas</p>
            <p class="font-semibold">
                {{ $contract->room->roomType->kapasitas }} orang
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Deskripsi</p>
            <p class="font-semibold">
                {{ $contract->room->roomType->deskripsi ?? '-' }}
            </p>
        </div>
    </div>
@endif

            @else
                <p class="text-gray-500">Belum ada kontrak aktif.</p>
            @endif
        </section>

        <!-- Tagihan -->
        <section class="bg-white rounded-xl shadow p-6 mb-8">
            <h3 class="text-lg font-bold mb-4">Tagihan Saya</h3>

            @if($contract && $contract->invoices->count())
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
                            @foreach($contract->invoices as $invoice)
                                <tr class="border-b">
                                    <td class="p-3">{{ $invoice->jenis_tagihan }}</td>
                                    <td class="p-3">
                                        Rp{{ number_format($invoice->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3">{{ $invoice->tanggal_jatuh_tempo }}</td>
                                    <td class="p-3">
                                        {{ ucfirst($invoice->status) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Belum ada tagihan.</p>
            @endif
        </section>

        <!-- Riwayat Pembayaran -->
        <section class="bg-white rounded-xl shadow p-6 mb-8">
            <h3 class="text-lg font-bold mb-4">Riwayat Pembayaran</h3>

            @forelse($payments as $payment)
                <div class="border-b py-3 flex justify-between gap-4">
                    <div>
                        <p class="font-semibold">
                            {{ $payment->nomor_pembayaran }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $payment->tanggal_bayar }}
                        </p>
                    </div>
                    <p class="font-semibold text-green-600">
                        Rp{{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500">Belum ada riwayat pembayaran.</p>
            @endforelse
        </section>

        <!-- Keluhan -->
        <section class="bg-white rounded-xl shadow p-6 mb-8">
            <h3 class="text-lg font-bold mb-4">Keluhan dan Pemeliharaan</h3>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('penghuni.keluhan.store') }}"
      method="POST"
      class="bg-gray-50 p-4 rounded-lg mb-6">

    @csrf

    <h4 class="font-semibold mb-3">Buat Laporan Keluhan</h4>

    <label class="block text-sm font-medium mb-1">
        Judul Keluhan
    </label>
    <input
        type="text"
        name="judul"
        maxlength="100"
        value="{{ old('judul') }}"
        placeholder="Contoh: Lampu kamar mati"
        class="w-full border rounded-lg p-3 mb-3"
        required
    >

    <label class="block text-sm font-medium mb-1">
        Deskripsi Keluhan
    </label>
    <textarea
        name="deskripsi"
        maxlength="500"
        rows="4"
        placeholder="Jelaskan kerusakan atau keluhan Anda..."
        class="w-full border rounded-lg p-3 mb-3"
        required
    >{{ old('deskripsi') }}</textarea>

    <button
        type="submit"
        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
        Kirim Keluhan
    </button>
</form>

            @forelse($tickets as $ticket)
                <div class="border-b py-3">
                    <p class="font-semibold">{{ $ticket->judul }}</p>
                    <p class="text-sm text-gray-500">{{ $ticket->deskripsi }}</p>
                    <p class="text-sm mt-1">
                        Status: {{ ucfirst($ticket->status) }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500">Belum ada keluhan yang dilaporkan.</p>
            @endforelse
        </section>

        <!-- Notifikasi -->
        <section class="bg-white rounded-xl shadow p-6 mb-8">
            <h3 class="text-lg font-bold mb-4">Notifikasi</h3>

            @forelse($notifications as $notification)
                <div class="border-b py-3">
                    <p>{{ $notification->pesan }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $notification->tipe_notifikasi }}
                    </p>
                </div>
            @empty
                <p class="text-gray-500">Belum ada notifikasi.</p>
            @endforelse
        </section>

    </main>
</body>
</html>
