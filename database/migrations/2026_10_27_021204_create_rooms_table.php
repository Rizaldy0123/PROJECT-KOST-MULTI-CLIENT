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
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('Property_id');
            $table->unsignedInteger('tipe_kamar_id')->index('idx_rooms_type');
            $table->string('nomor_kamar', 10);
            $table->decimal('harga', 10)->default(0);
            $table->enum('status', ['tersedia', 'terisi', 'perbaikan', 'nonaktif'])->default('tersedia');
            $table->string('foto')->nullable();
            $table->timestamp('create_at')->useCurrent();

            $table->unique(['Property_id', 'nomor_kamar'], 'uq_rooms_property_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
