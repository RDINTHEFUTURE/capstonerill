<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('tanggal');
            $table->string('npwp', 32)->nullable();
            $table->string('nama')->nullable();
            $table->string('alamat')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->longText('qr_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

