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
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('room_id')->index('idx_contracts_room');
            $table->unsignedInteger('client_id')->index('idx_contracts_client');
            $table->unsignedInteger('client_user_id')->index('idx_contracts_client_user');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->timestamp('create_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
