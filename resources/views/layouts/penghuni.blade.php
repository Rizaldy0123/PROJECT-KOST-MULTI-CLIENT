<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Penghuni - KostWangi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

@php
    $menus = [
        ['route' => 'penghuni.dashboard',  'match' => ['penghuni.dashboard', 'penghuni.sewa.*'], 'label' => 'Informasi & Sewa Kost', 'icon' => 'M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10'],
        ['route' => 'penghuni.tagihan',    'match' => ['penghuni.tagihan'],    'label' => 'Tagihan Saya',   'icon' => 'M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-3-2-2 2-2-2-2 2-2-2-3 2V6a2 2 0 012-2z'],
        ['route' => 'penghuni.pembayaran', 'match' => ['penghuni.pembayaran'], 'label' => 'Riwayat Pembayaran', 'icon' => 'M3 10h18M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z'],
        ['route' => 'penghuni.keluhan',    'match' => ['penghuni.keluhan'],    'label' => 'Keluhan & Pemeliharaan', 'icon' => 'M10.3 4.3l-7 12A2 2 0 005 19h14a2 2 0 001.7-3l-7-12a2 2 0 00-3.4 0zM12 9v4m0 4h.01'],
        ['route' => 'penghuni.notifikasi', 'match' => ['penghuni.notifikasi'], 'label' => 'Notifikasi',     'icon' => 'M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0'],
    ];
@endphp

<div class="flex min-h-screen">

    <!-- Overlay (mobile) -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-200">
        <div class="px-6 py-5 border-b">
            <h1 class="text-xl font-bold text-blue-700">KostWangi</h1>
            <p class="text-xs text-gray-500 mt-1">Area Penghuni</p>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @foreach($menus as $menu)
                @php $active = request()->routeIs(...$menu['match']); @endphp
                <a href="{{ route($menu['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
                          {{ $active ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}"/>
                    </svg>
                    {{ $menu['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="border-t p-4">
            <p class="text-sm font-semibold text-gray-800 truncate">{{ $user->nama }}</p>
            <p class="text-xs text-gray-500 truncate mb-3">{{ $user->email }}</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600 transition">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Konten -->
    <div class="flex-1 md:ml-64 min-w-0">
        <!-- Topbar mobile -->
        <header class="md:hidden bg-white shadow sticky top-0 z-20 px-4 py-3 flex items-center gap-3">
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100" aria-label="Buka menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-bold text-blue-700">KostWangi</span>
        </header>

        <main class="max-w-6xl mx-auto px-4 md:px-8 py-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebar-overlay').classList.toggle('hidden');
    }
</script>
</body>
</html>
