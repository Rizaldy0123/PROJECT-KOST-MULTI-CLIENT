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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign(['client_id'], 'fk_invoices_client')->references(['ID'])->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['room_assignment_id'], 'fk_invoices_contract')->references(['ID'])->on('contracts')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('fk_invoices_client');
            $table->dropForeign('fk_invoices_contract');
        });
    }
};
