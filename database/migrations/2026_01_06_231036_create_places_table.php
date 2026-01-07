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
        Schema::create('places', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['castle', 'cultural_property'])->index();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('prefecture_id')->constrained('prefectures');

            $table->string('slug', 120)->unique();

            $table->string('name_ja', 120);
            $table->string('name_en', 160)->nullable();
            $table->string('kana', 160)->nullable()->index();

            $table->string('short_desc_ja', 200)->nullable();
            $table->string('short_desc_en', 240)->nullable();

            $table->text('description_ja')->nullable();
            $table->text('description_en')->nullable();

            $table->string('address_ja')->nullable();
            $table->string('address_en')->nullable();

            $table->decimal('lat', 10, 7)->nullable()->index();
            $table->decimal('lng', 10, 7)->nullable()->index();

            $table->string('built_year', 20)->nullable();
            $table->string('builder_ja', 120)->nullable();
            $table->string('builder_en', 160)->nullable();

            $table->string('abolished_year', 20)->nullable();

            $table->text('main_lords_ja')->nullable();
            $table->text('main_lords_en')->nullable();

            $table->string('renovator_ja', 120)->nullable();
            $table->string('renovator_en', 160)->nullable();

            $table->string('castle_style_ja', 80)->nullable();
            $table->string('castle_style_en', 120)->nullable();

            $table->string('tenshu_style_ja', 80)->nullable();
            $table->string('tenshu_style_en', 120)->nullable();

            $table->string('heritage_designation_ja', 120)->nullable();
            $table->string('heritage_designation_en', 160)->nullable();

            $table->text('remains_ja')->nullable();
            $table->text('remains_en')->nullable();

            $table->tinyInteger('rating')->unsigned()->nullable()->index();

            $table->boolean('is_published')->default(false)->index();
            $table->dateTime('published_at')->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
