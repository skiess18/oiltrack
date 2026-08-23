<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('recipients');
            $table->timestamps();
        });

        Schema::create('client_email_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->timestamps();
            $table->unique(['client_id', 'email']);
        });

        DB::table('clients')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('id')
            ->each(function (object $client): void {
                DB::table('client_email_recipients')->insertOrIgnore([
                    'client_id' => $client->id,
                    'email' => strtolower(trim($client->email)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_email_recipients');
        Schema::dropIfExists('notification_settings');
    }
};
