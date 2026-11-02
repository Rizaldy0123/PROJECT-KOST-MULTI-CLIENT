<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Properti - KostWangi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Properti Kost</h1>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Dashboard</a>
            </div>
            <a href="{{ route('admin.properties.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 font-medium">
                + Tambah Properti
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
                    <tr>
                        <th class="p-4">Nama Properti</th>
                        <th class="p-4">Alamat</th>
                        <th class="p-4">Jumlah Kamar</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties as $property)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-semibold text-gray-800">{{ $property->nama_properti }}</td>
                            <td class="p-4">{{ $property->alamat }}</td>
                            <td class="p-4">{{ $property->rooms_count }} Kamar</td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $property->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <a href="{{ route('admin.properties.edit', $property->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                                <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus properti ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-400">Belum ada properti yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>