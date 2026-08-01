<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {

            if (!Schema::hasColumn('clients', 'company_name')) {
                $table->string('company_name')->nullable();
            }

            if (!Schema::hasColumn('clients', 'bulstat')) {
                $table->string('bulstat', 20)->nullable();
            }

            if (!Schema::hasColumn('clients', 'payment_method')) {
                $table->enum('payment_method', [
                    'cash',
                    'bank_transfer',
                ])->nullable();
            }

            if (!Schema::hasColumn('clients', 'price_per_liter')) {
                $table->decimal('price_per_liter', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('clients', 'visit_interval_days')) {
                $table->integer('visit_interval_days')->nullable();
            }

        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {

            $columns = [];

            foreach ([
                'company_name',
                'bulstat',
                'payment_method',
                'price_per_liter',
                'visit_interval_days',
            ] as $column) {
                if (Schema::hasColumn('clients', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }

        });
    }
};