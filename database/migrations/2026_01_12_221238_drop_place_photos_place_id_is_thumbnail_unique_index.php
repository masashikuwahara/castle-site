<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('place_photos', function (Blueprint $table) {
            // FK維持のために、place_id単独のインデックスを先に作る
            // ※名前を固定して重複を避ける
            $table->index('place_id', 'place_photos_place_id_idx');
        });

        Schema::table('place_photos', function (Blueprint $table) {
            // UNIQUE(place_id, is_thumbnail) を落とす
            $table->dropUnique('place_photos_place_id_is_thumbnail_unique');
        });
    }

    public function down(): void
    {
        Schema::table('place_photos', function (Blueprint $table) {
            // UNIQUEを戻す（元の名前で復元）
            $table->unique(['place_id', 'is_thumbnail'], 'place_photos_place_id_is_thumbnail_unique');

            // 追加したINDEXを落とす
            $table->dropIndex('place_photos_place_id_idx');
        });
    }
};
