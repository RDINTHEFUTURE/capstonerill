<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_items', 'chart_of_account_no_new')) {
                $table->string('chart_of_account_no_new', 6)->nullable()->after('invoice_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_items', 'chart_of_account_no_new')) {
                $table->dropColumn('chart_of_account_no_new');
            }
        });
    }
};
