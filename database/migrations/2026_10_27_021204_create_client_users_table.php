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
        Schema::create('client_users', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('user_id')->index('idx_client_users_user');
            $table->enum('role', ['owner', 'admin', 'staff', 'tenant'])->default('tenant');
            $table->boolean('is_active')->default(true);
            $table->timestamp('create_at')->useCurrent();

            $table->unique(['client_id', 'user_id'], 'uq_client_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_users');
    }
};
