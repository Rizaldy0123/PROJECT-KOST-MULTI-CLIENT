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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign(['client_user_id'], 'fk_payments_client_user')->references(['ID'])->on('client_users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['invoice_id'], 'fk_payments_invoice')->references(['ID'])->on('invoices')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('fk_payments_client_user');
            $table->dropForeign('fk_payments_invoice');
        });
    }
};
