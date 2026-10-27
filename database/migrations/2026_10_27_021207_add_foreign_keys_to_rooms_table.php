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
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign(['Property_id'], 'fk_rooms_property')->references(['ID'])->on('properties')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['tipe_kamar_id'], 'fk_rooms_type')->references(['ID'])->on('room_types')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign('fk_rooms_property');
            $table->dropForeign('fk_rooms_type');
        });
    }
};
