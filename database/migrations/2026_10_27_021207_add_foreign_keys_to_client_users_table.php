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
        Schema::table('client_users', function (Blueprint $table) {
            $table->foreign(['client_id'], 'fk_client_users_client')->references(['ID'])->on('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_client_users_user')->references(['ID'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_users', function (Blueprint $table) {
            $table->dropForeign('fk_client_users_client');
            $table->dropForeign('fk_client_users_user');
        });
    }
};
