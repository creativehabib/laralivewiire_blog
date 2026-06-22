<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->string('device')->nullable()->after('user_agent');
            $table->string('browser')->nullable()->after('device');
            $table->string('platform')->nullable()->after('browser');
            $table->string('location')->nullable()->after('platform');
        });
    }

    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn(['device', 'browser', 'platform', 'location']);
        });
    }
};
