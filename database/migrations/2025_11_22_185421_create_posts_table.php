<?php

use App\Models\User;
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
        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('description', 400)->nullable();
            $table->longText('content')->nullable();
            $table->string('slug')->unique();
            $table->string('status', 60)->default('published');

            $table->foreignId('author_id')->nullable();
            $table->string('author_type')->default(User::class);

            $table->tinyInteger('is_featured')->unsigned()->default(0);
            $table->string('image')->nullable();
            $table->integer('views')->unsigned()->default(0);

            $table->boolean('allow_comments')->default(false);
            $table->boolean('is_breaking')->default(false);
            $table->string('format_type', 30)->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status', 'post_status_index');
            $table->index('author_id', 'post_author_id_index');
            $table->index('author_type', 'post_author_type_index');
            $table->index('created_at', 'posts_created_at_index');
            $table->index('views', 'post_views_index');
        });


        Schema::create('post_tags', function (Blueprint $table): void {
            $table->foreignId('tag_id')->index();
            $table->foreignId('post_id')->index();
        });

        Schema::create('post_categories', function (Blueprint $table): void {
            $table->foreignId('category_id')->index();
            $table->foreignId('post_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_tags');
        Schema::dropIfExists('post_categories');
    }
};
