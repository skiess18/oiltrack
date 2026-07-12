<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {

            $table->id();

            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            $table->date('collection_date');

            $table->decimal('liters', 10, 2);

            $table->decimal('price_per_liter', 10, 2)->default(0);

            $table->decimal('total_price', 10, 2)->default(0);

            $table->text('notes')->nullable();

            $table->string('image')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};