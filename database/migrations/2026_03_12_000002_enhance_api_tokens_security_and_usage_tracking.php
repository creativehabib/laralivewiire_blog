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
        Schema::table('api_tokens', function (Blueprint $table): void {
            $table->json('abilities')->nullable()->after('token_hash');
            $table->string('last_used_ip', 45)->nullable()->after('last_used_at');
        });

        Schema::create('api_token_request_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('api_token_id')->constrained('api_tokens')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['api_token_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_token_request_logs');

        Schema::table('api_tokens', function (Blueprint $table): void {
            $table->dropColumn(['abilities', 'last_used_ip']);
        });
    }
};
