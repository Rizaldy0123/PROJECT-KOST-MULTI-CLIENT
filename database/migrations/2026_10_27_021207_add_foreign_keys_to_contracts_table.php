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
        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign(['client_id'], 'fk_contracts_client')->references(['ID'])->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['client_user_id'], 'fk_contracts_client_user')->references(['ID'])->on('client_users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['room_id'], 'fk_contracts_room')->references(['ID'])->on('rooms')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign('fk_contracts_client');
            $table->dropForeign('fk_contracts_client_user');
            $table->dropForeign('fk_contracts_room');
        });
    }
};
