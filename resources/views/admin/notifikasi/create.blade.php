
@extends('layouts.app')

@section('title', 'Kirim Notifikasi - KostWangi')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-lg shadow p-6">

        <h2 class="text-xl font-bold text-gray-800 mb-2">
            Kirim Notifikasi
        </h2>

        <p class="text-gray-500 mb-6">
            Kirim informasi kepada penghuni KostWangi.
        </p>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.notifikasi.store') }}"
              method="POST"
              class="space-y-5">

            @csrf

            <div>
                <label class="block font-medium text-gray-700 mb-2">
                    Tujuan Notifikasi
                </label>

                <select name="tujuan"
                        id="tujuan"
                        required
                        class="w-full border rounded-md p-3">

                    <option value="semua">
                        Semua Penghuni
                    </option>

                    <option value="tertentu">
                        Penghuni Tertentu
                    </option>

                </select>
            </div>

            <div id="pilih-penghuni" class="hidden">

                <label class="block font-medium text-gray-700 mb-2">
                    Pilih Penghuni
                </label>

                <select name="user_id"
                        id="user_id"
                        class="w-full border rounded-md p-3">

                    <option value="">
                        -- Pilih Penghuni --
                    </option>

                    @foreach ($penghuni as $item)

                        <option value="{{ $item->user_id }}">
                            {{ $item->user->name ?? $item->user->nama ?? 'Tanpa Nama' }}
                            ({{ $item->user->email ?? '-' }})
                        </option>

                    @endforeach

                </select>

            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-2">
                    Kategori Notifikasi
                </label>

                <select name="tipe_notifikasi"
                        required
                        class="w-full border rounded-md p-3">

                    <option value="pengumuman">Pengumuman</option>
                    <option value="tagihan">Tagihan</option>
                    <option value="pembayaran">Pembayaran</option>
                    <option value="pemeliharaan">Pemeliharaan</option>
                    <option value="kontrak">Kontrak</option>

                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-2">
                    Isi Notifikasi
                </label>

                <textarea name="pesan"
                          rows="5"
                          maxlength="500"
                          required
                          placeholder="Tuliskan informasi untuk penghuni..."
                          class="w-full border rounded-md p-3">{{ old('pesan') }}</textarea>

                <p class="text-sm text-gray-400 mt-1">
                    Maksimal 500 karakter.
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-4">

                <a href="{{ route('admin.notifikasi.index') }}"
                   class="px-5 py-2 border rounded-md text-gray-600">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Kirim Notifikasi
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    const tujuan = document.getElementById('tujuan');
    const pilihPenghuni = document.getElementById('pilih-penghuni');
    const userId = document.getElementById('user_id');

    function aturTujuan() {
        const tertentu = tujuan.value === 'tertentu';

        pilihPenghuni.classList.toggle('hidden', !tertentu);
        userId.required = tertentu;
    }

    tujuan.addEventListener('change', aturTujuan);

    aturTujuan();
</script>

@endsection
