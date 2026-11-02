<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\User;
use App\Models\ClientUser;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\MaintenanceTicket;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data User
        $adminUser = User::create([
            'nama' => 'Admin Platform Utama',
            'email' => 'admin@kostwangi.com',
            'password_hash' => Hash::make('password123'),
            'no_hp' => '081234567890',
            'status' => 'aktif',
        ]);

        $ownerUser = User::create([
            'nama' => 'Budi Santoso (Owner)',
            'email' => 'budi@kostmutiara.com',
            'password_hash' => Hash::make('password123'),
            'no_hp' => '082198765432',
            'status' => 'aktif',
        ]);

        $penghuniUser = User::create([
            'nama' => 'Rizky Pratama (Penghuni)',
            'email' => 'rizky@gmail.com',
            'password_hash' => Hash::make('password123'),
            'no_hp' => '085712345678',
            'status' => 'aktif',
        ]);

        // 2. Data Client (Pengelola Kost)
        $client = Client::create([
            'nama_tenant' => 'Kost Mutiara Banyuwangi',
            'domain' => 'mutiara.kostwangi.com',
            'alamat' => 'Jl. Merdeka No. 45, Banyuwangi',
            'kontak' => '082198765432',
            'aktif' => 1,
        ]);

        // 3. Data Client Users (Pivot Peran/Akses)
        $clientOwner = ClientUser::create([
            'client_id' => $client->id,
            'user_id' => $ownerUser->id,
            'role' => 'admin_client',
            'is_active' => 1,
        ]);

        $clientPenghuni = ClientUser::create([
            'client_id' => $client->id,
            'user_id' => $penghuniUser->id,
            'role' => 'penghuni',
            'is_active' => 1,
        ]);

        // 4. Data Properti Kost
        $property = Property::create([
            'client_id' => $client->id,
            'nama_properti' => 'Kost Mutiara Kampus',
            'alamat' => 'Jl. Raya Kampus Poliwangi No. 12',
            'deskripsi' => 'Kost eksklusif dekat kampus dengan fasilitas lengkap.',
            'status' => 'aktif',
        ]);

        // 5. Data Tipe Kamar
        $roomTypeDeluxe = RoomType::create([
            'client_id' => $client->id,
            'nama_tipe' => 'Deluxe AC',
            'deskripsi' => 'Kamar mandi dalam, AC, Wi-Fi, Kasur Springbed',
            'harga_default' => 850000.00,
            'kapasitas' => 1,
        ]);

        // 6. Data Kamar
        $room101 = Room::create([
            'property_id' => $property->id,
            'tipe_kamar_id' => $roomTypeDeluxe->id,
            'nomor_kamar' => '101',
            'harga' => 850000.00,
            'status' => 'terisi',
            'foto' => null,
        ]);

        $room102 = Room::create([
            'property_id' => $property->id,
            'tipe_kamar_id' => $roomTypeDeluxe->id,
            'nomor_kamar' => '102',
            'harga' => 850000.00,
            'status' => 'tersedia',
            'foto' => null,
        ]);

        // 7. Data Kontrak Sewa
        $contract = Contract::create([
            'room_id' => $room101->id,
            'client_id' => $client->id,
            'client_user_id' => $clientPenghuni->id,
            'start_date' => '2026-10-01',
            'end_date' => '2027-09-30',
            'status' => 'aktif',
        ]);

        // 8. Data Tagihan (Invoice)
        $invoice = Invoice::create([
            'client_id' => $client->id,
            'room_assignment_id' => $contract->id,
            'jenis_tagihan' => 'sewa',
            'jumlah' => 850000.00,
            'tanggal_jatuh_tempo' => '2026-11-05',
            'status' => 'lunas',
        ]);

        // 9. Data Pembayaran
        Payment::create([
            'invoice_id' => $invoice->id,
            'client_user_id' => $clientPenghuni->id,
            'metode_pembayaran' => 'Transfer Bank BCA',
            'jumlah' => 850000.00,
            'bukti_pembayaran' => 'bukti_101.jpg',
            'status' => 'diverifikasi',
            'tanggal_bayar' => now(),
        ]);

        // 10. Data Tiket Keluhan / Pemeliharaan
        MaintenanceTicket::create([
            'client_id' => $client->id,
            'room_id' => $room101->id,
            'pelapor_id' => $clientPenghuni->id,
            'judul' => 'Kran Air Kamar Mandi Bocor',
            'deskripsi' => 'Kran air di kamar mandi 101 menetes terus menerus.',
            'status' => 'diproses',
            'tanggal_lapor' => now(),
        ]);

        // 11. Data Notifikasi
        Notification::create([
            'client_id' => $client->id,
            'user_id' => $penghuniUser->id,
            'tipe_notifikasi' => 'pembayaran',
            'pesan' => 'Pembayaran sewa bulan November telah diverifikasi.',
            'status_baca' => 0,
        ]);
    }
}