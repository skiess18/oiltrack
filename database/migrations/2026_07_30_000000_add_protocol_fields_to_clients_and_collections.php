<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {

            if (!Schema::hasColumn('clients', 'representative')) {
                $table->string('representative')->nullable()->after('bulstat');
            }

            if (!Schema::hasColumn('clients', 'email')) {
                $table->string('email')->nullable()->after('representative');
            }

        });

        Schema::table('collections', function (Blueprint $table) {

            if (!Schema::hasColumn('collections', 'protocol_path')) {
                $table->string('protocol_path')->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('collections', 'cash_receipt_path')) {
                $table->string('cash_receipt_path')->nullable()->after('protocol_path');
            }

            if (!Schema::hasColumn('collections', 'protocol_sent_at')) {
                $table->timestamp('protocol_sent_at')->nullable()->after('cash_receipt_path');
            }

        });
    }

    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('collections', 'protocol_path')) {
                $columns[] = 'protocol_path';
            }

            if (Schema::hasColumn('collections', 'cash_receipt_path')) {
                $columns[] = 'cash_receipt_path';
            }

            if (Schema::hasColumn('collections', 'protocol_sent_at')) {
                $columns[] = 'protocol_sent_at';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }

        });

        Schema::table('clients', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('clients', 'representative')) {
                $columns[] = 'representative';
            }

            if (Schema::hasColumn('clients', 'email')) {
                $columns[] = 'email';
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }

        });
    }
};