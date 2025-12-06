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
        Schema::create('meta_boxes', function (Blueprint $table) {
            $table->id();

            $table->string('meta_key');
            $table->longText('meta_value');
            // polymorphic relation (model + id)
            $table->unsignedBigInteger('reference_id');
            $table->string('reference_type');
            $table->timestamps();

            // চাইলে index:
            $table->index(['reference_id', 'reference_type']);
            $table->index('meta_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_boxes');
    }
};
