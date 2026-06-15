<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'signature_data')) {
                $table->longText('signature_data')->nullable()->after('qr_payload');
            }

            if (!Schema::hasColumn('invoices', 'signature_data_uri')) {
                $table->longText('signature_data_uri')->nullable()->after('signature_data');
            }

            if (!Schema::hasColumn('invoices', 'signature_name')) {
                $table->string('signature_name')->nullable()->after('signature_data_uri');
            }

            if (!Schema::hasColumn('invoices', 'signature_signed_at')) {
                $table->timestamp('signature_signed_at')->nullable()->after('signature_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            foreach ([
                'signature_signed_at',
                'signature_name',
                'signature_data_uri',
                'signature_data',
            ] as $column) {
                if (Schema::hasColumn('invoices', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
