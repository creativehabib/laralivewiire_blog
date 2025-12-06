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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('image')->nullable();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('icon')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['draft','pending', 'published'])->default('published');

            // author (polymorphic)
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('author_type')->nullable();

            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')
                ->nullOnDelete();

            $table->index(['author_id', 'author_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
