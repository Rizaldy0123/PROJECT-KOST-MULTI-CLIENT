<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('client_user_id')->constrained('client_users')->onDelete('cascade');
            $table->string('metode_pembayaran', 50);
            $table->decimal('jumlah', 10, 2);
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->enum('status', ['pending', 'diverifikasi', 'ditolak'])->default('pending');
            $table->timestamp('tanggal_bayar')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};