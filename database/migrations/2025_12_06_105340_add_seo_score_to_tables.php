<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['posts', 'categories', 'tags'] as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'seo_score')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->unsignedTinyInteger('seo_score')
                        ->nullable()
                        ->after('status'); // যেখানেই সুবিধা লাগে
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['posts', 'categories', 'tags'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'seo_score')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('seo_score');
                });
            }
        }
    }
};
