<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_reports', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('vehicle_id')
                ->nullable()
                ->constrained('vehicles')
                ->nullOnDelete();

            $table->date('date');

            $table->integer('start_km');

            $table->integer('end_km')->nullable();

            $table->unsignedTinyInteger('start_fuel');

            $table->unsignedTinyInteger('end_fuel')->nullable();

            $table->string('receipt')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_reports');
    }
};