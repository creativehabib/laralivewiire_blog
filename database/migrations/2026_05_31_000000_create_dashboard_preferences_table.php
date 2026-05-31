<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dashboard_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('key')->default('admin_dashboard');
            $table->json('preferences')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'key']);
        });

        if (Schema::hasColumn('users', 'dashboard_preferences')) {
            $now = now();

            DB::table('users')
                ->whereNotNull('dashboard_preferences')
                ->orderBy('id')
                ->select(['id', 'dashboard_preferences'])
                ->chunkById(100, function ($users) use ($now) {
                    $rows = $users->map(fn ($user) => [
                        'user_id' => $user->id,
                        'key' => 'admin_dashboard',
                        'preferences' => $user->dashboard_preferences,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->all();

                    if ($rows !== []) {
                        DB::table('dashboard_preferences')->insertOrIgnore($rows);
                    }
                });

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('dashboard_preferences');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('users', 'dashboard_preferences')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('dashboard_preferences')->nullable()->after('remember_token');
            });
        }

        if (Schema::hasTable('dashboard_preferences')) {
            DB::table('dashboard_preferences')
                ->where('key', 'admin_dashboard')
                ->orderBy('id')
                ->select(['id', 'user_id', 'preferences'])
                ->chunkById(100, function ($preferences) {
                    $preferences->each(function ($preference) {
                        DB::table('users')
                            ->where('id', $preference->user_id)
                            ->update(['dashboard_preferences' => $preference->preferences]);
                    });
                });
        }

        Schema::dropIfExists('dashboard_preferences');
    }
};
