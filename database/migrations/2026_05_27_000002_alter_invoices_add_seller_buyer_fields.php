<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Seller (Penjual)
            $table->string('npwp_penjual', 32)->nullable();
            $table->string('nama_penjual', 255)->nullable();
            $table->string('alamat_penjual', 255)->nullable();

            // Buyer (Pembeli)
            $table->string('npwp_pembeli', 32)->nullable();
            $table->string('nama_pembeli', 255)->nullable();
            $table->string('alamat_pembeli', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'npwp_penjual',
                'nama_penjual',
                'alamat_penjual',
                'npwp_pembeli',
                'nama_pembeli',
                'alamat_pembeli',
            ]);
        });
    }
};

