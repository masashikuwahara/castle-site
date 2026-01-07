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
        Schema::create('place_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('place_id')->constrained()->cascadeOnDelete()->index();

            $table->string('path');
            $table->string('caption_ja', 200)->nullable();
            $table->string('caption_en', 240)->nullable();

            $table->date('taken_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_thumbnail')->default(false);

            $table->timestamps();

            $table->unique(['place_id', 'is_thumbnail']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_photos');
    }
};
