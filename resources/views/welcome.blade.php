<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Properti Kost</title>
    
    <!-- Tambahkan baris Vite ini supaya Tailwind aktif -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 30px;">
    
    <div style="max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h1 style="color: #333;">Dashboard Properti Kost</h1>
        <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">

        <!-- Memanggil Komponen Livewire Kamu -->
        <livewire:property-manager />
    </div>

    @livewireScripts
</body>
</html>