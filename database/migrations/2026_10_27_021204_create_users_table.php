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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('nama', 100);
            $table->string('email', 100)->unique('uq_users_email');
            $table->string('password_hash');
            $table->string('no_hp', 20)->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'pending'])->default('aktif');
            $table->timestamp('create_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
