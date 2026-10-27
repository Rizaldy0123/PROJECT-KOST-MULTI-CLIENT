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
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('client_id')->index('idx_invoices_client');
            $table->unsignedInteger('room_assignment_id')->nullable()->index('idx_invoices_assignment');
            $table->enum('jenis_tagihan', ['sewa', 'listrik', 'air', 'denda', 'lainnya'])->default('sewa');
            $table->decimal('jumlah', 10)->default(0);
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['belum_dibayar', 'menunggu', 'lunas', 'terlambat', 'dibatalkan'])->default('belum_dibayar');
            $table->timestamp('create_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
