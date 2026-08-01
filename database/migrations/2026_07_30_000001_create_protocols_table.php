<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('protocols')) {
            Schema::create('protocols', function (Blueprint $table) {
                $table->id();

                $table->foreignId('collection_id')
                    ->unique()
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('client_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();

                $table->string('pdf_path')->nullable();

                $table->boolean('email_sent_to_owner')->default(false);
                $table->boolean('email_sent_to_client')->default(false);

                $table->timestamp('sent_at')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('protocols');
    }
};