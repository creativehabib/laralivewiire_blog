<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            if (Schema::hasColumn('posts', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('pages', function (Blueprint $table): void {
            if (Schema::hasColumn('pages', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('categories', function (Blueprint $table): void {
            if (Schema::hasColumn('categories', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('tags', function (Blueprint $table): void {
            if (Schema::hasColumn('tags', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            if (! Schema::hasColumn('posts', 'slug')) {
                $table->string('slug')->unique()->after('content');
            }
        });

        Schema::table('pages', function (Blueprint $table): void {
            if (! Schema::hasColumn('pages', 'slug')) {
                $table->string('slug')->unique()->after('description');
            }
        });

        Schema::table('categories', function (Blueprint $table): void {
            if (! Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->unique()->after('description');
            }
        });

        Schema::table('tags', function (Blueprint $table): void {
            if (! Schema::hasColumn('tags', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
        });
    }
};
