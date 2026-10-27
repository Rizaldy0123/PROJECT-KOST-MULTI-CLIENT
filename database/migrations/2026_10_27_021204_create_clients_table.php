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
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('Nama_Tenant', 100);
            $table->string('domain', 100)->nullable()->unique('uq_clients_domain');
            $table->string('alamat')->nullable();
            $table->string('kontak', 50)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('create_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
