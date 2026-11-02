<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('room_assignment_id')->constrained('contracts')->onDelete('cascade');
            $table->enum('jenis_tagihan', ['sewa', 'listrik', 'air', 'denda', 'lainnya'])->default('sewa');
            $table->decimal('jumlah', 10, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['belum_bayar', 'lunas', 'terlambat', 'dibatalkan'])->default('belum_bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};