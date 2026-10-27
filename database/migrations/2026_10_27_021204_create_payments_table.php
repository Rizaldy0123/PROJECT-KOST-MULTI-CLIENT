<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('invoice_id')->index('idx_payments_invoice');
            $table->unsignedInteger('client_user_id')->index('idx_payments_client_user');
            $table->string('metode_pembayaran', 50);
            $table->decimal('jumlah', 10)->default(0);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->timestamp('tanggal_bayar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
