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
        Schema::create('vehicles', function (Blueprint $table) {

            $table->id();

            $table->string('brand');
            $table->string('model');
            $table->year('year')->nullable();

            $table->string('registration')->unique();

            $table->string('vin')->nullable();

            $table->string('color')->nullable();

            $table->string('photo')->nullable();

            $table->string('driver')->nullable();

            $table->decimal('fuel_consumption',5,2)->nullable();

            $table->integer('current_km')->default(0);

            $table->date('last_service')->nullable();

            $table->integer('next_service_km')->nullable();

            $table->date('inspection_date')->nullable();

            $table->date('insurance_date')->nullable();

            $table->enum('status',[
                'active',
                'service',
                'inactive'
            ])->default('active');

            $table->text('notes')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};