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
        Schema::table('maintenance_tickets', function (Blueprint $table) {
            $table->foreign(['client_id'], 'fk_maintenance_client')->references(['ID'])->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['pelapor_id'], 'fk_maintenance_reporter')->references(['ID'])->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['room_id'], 'fk_maintenance_room')->references(['ID'])->on('rooms')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_tickets', function (Blueprint $table) {
            $table->dropForeign('fk_maintenance_client');
            $table->dropForeign('fk_maintenance_reporter');
            $table->dropForeign('fk_maintenance_room');
        });
    }
};
