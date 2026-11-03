<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KostWangi Multi Client')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Master Navbar Header -->
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <h1 class="text-xl font-bold text-gray-800">KostWangi Admin</h1>
                
                <!-- Menu Navigasi Utama -->
                <nav class="hidden md:flex space-x-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.properties.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.properties.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                        Data Properti
                    </a>

                    <a href="{{ route('admin.room-types.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.room-types.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                        Tipe Kamar
                    </a>

                    <a href="{{ route('admin.rooms.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.rooms.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                        Data Kamar
                    </a>

                    <a href="{{ route('admin.contracts.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.contracts.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                        Kontrak
                    </a>

                    <!-- Navigasi Tagihan yang Telah Diaktifkan -->
                    <a href="{{ route('admin.invoices.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.invoices.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                        Tagihan
                    </a>
                </nav>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600 transition">Logout</button>
            </form>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Flash Notification Alert Global -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>