<?php

use App\Models\Admin\Page;
use App\Models\Admin\Tag;
use App\Models\Category;
use App\Models\Post;
use App\Models\Slug;
use App\Support\SlugHelper;
use App\Support\SlugService;
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
        Schema::create('slugs', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('prefix')->nullable();
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id');
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
            $table->index('prefix');
        });

        $this->backfillSlugs();
    }

    public function down(): void
    {
        Schema::dropIfExists('slugs');
    }

    protected function backfillSlugs(): void
    {
        $targets = [
            ['table' => 'posts', 'model' => Post::class],
            ['table' => 'pages', 'model' => Page::class],
            ['table' => 'categories', 'model' => Category::class],
            ['table' => 'tags', 'model' => Tag::class],
        ];

        foreach ($targets as $target) {
            $table = $target['table'];
            $modelClass = $target['model'];
            $model = new $modelClass();
            $prefix = SlugHelper::prefixForModel($model);

            DB::table($table)
                ->select('id', 'slug')
                ->whereNotNull('slug')
                ->orderBy('id')
                ->chunkById(200, function ($rows) use ($modelClass, $prefix): void {
                    foreach ($rows as $row) {
                        $key = SlugService::create((string) $row->slug, $prefix);

                        Slug::create([
                            'key' => $key,
                            'prefix' => $prefix,
                            'reference_type' => $modelClass,
                            'reference_id' => $row->id,
                        ]);
                    }
                });
        }
    }
};
