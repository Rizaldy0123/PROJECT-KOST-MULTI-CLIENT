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
        Schema::create('maintenance_tickets', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('client_id')->index('idx_maintenance_client');
            $table->unsignedInteger('room_id')->nullable()->index('idx_maintenance_room');
            $table->unsignedInteger('pelapor_id')->index('idx_maintenance_reporter');
            $table->string('judul', 100);
            $table->string('deskripsi', 500)->nullable();
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->timestamp('tanggal_lapor')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_tickets');
    }
};
