<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengelola - KostWangi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navbar Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <h1 class="text-xl font-bold text-gray-800">KostWangi Multi Client - Admin Panel</h1>
                <!-- Menu Navigasi -->
                <nav class="hidden md:flex space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">Dashboard</a>
                    <a href="{{ route('admin.properties.index') }}" class="text-gray-600 hover:text-blue-600 font-medium pb-1">Data Properti</a>
                </nav>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600 transition">Logout</button>
            </form>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Ringkasan Statistik</h2>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card Properti dengan Link -->
            <a href="{{ route('admin.properties.index') }}" class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500 hover:shadow-md transition block group">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-500 group-hover:text-blue-600">Total Properti Kost</p>
                    <span class="text-xs text-blue-600 group-hover:underline">Kelola &rarr;</span>
                </div>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalProperties }}</p>
            </a>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-500">Status Kamar</p>
                <p class="text-xl font-bold text-gray-800 mt-2">
                    <span class="text-green-600">{{ $kamarTerisi }} Terisi</span> / 
                    <span class="text-gray-500">{{ $kamarTersedia }} Kosong</span>
                </p>
                <p class="text-xs text-gray-400 mt-1">Total Unit: {{ $totalRooms }} Kamar</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-emerald-500">
                <p class="text-sm font-medium text-gray-500">Pendapatan Bulan Ini</p>
                <p class="text-2xl font-bold text-emerald-600 mt-2">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-amber-500">
                <p class="text-sm font-medium text-gray-500">Tiket Perbaikan Aktif</p>
                <p class="text-3xl font-bold text-amber-600 mt-2">{{ $tiketAktif }}</p>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Tabel Tiket Perbaikan Terbaru -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Laporan Kerusakan Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="p-3">Kamar</th>
                                <th class="p-3">Judul</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tiketTerbaru as $tiket)
                                <tr class="border-b">
                                    <td class="p-3 font-medium">Kamar {{ $tiket->room->nomor_kamar ?? '-' }}</td>
                                    <td class="p-3">{{ $tiket->judul }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">{{ ucfirst($tiket->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-3 text-center text-gray-400">Belum ada keluhan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Tagihan Terbaru -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tagihan & Sewa Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="p-3">Jenis</th>
                                <th class="p-3">Jumlah</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tagihanTerbaru as $invoice)
                                <tr class="border-b">
                                    <td class="p-3 font-medium capitalize">{{ $invoice->jenis_tagihan }}</td>
                                    <td class="p-3">Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 text-xs rounded {{ $invoice->status == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-3 text-center text-gray-400">Belum ada tagihan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>