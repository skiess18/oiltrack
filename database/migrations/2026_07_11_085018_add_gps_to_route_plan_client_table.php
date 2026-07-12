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
        Schema::table('route_plan_client', function (Blueprint $table) {

            $table->timestamp('arrived_at')->nullable()->after('visited');

            $table->decimal('latitude', 10, 7)
                ->nullable()
                ->after('arrived_at');

            $table->decimal('longitude', 10, 7)
                ->nullable()
                ->after('latitude');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('route_plan_client', function (Blueprint $table) {

            $table->dropColumn([
                'arrived_at',
                'latitude',
                'longitude',
            ]);

        });
    }
};