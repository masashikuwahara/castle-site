<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('places', function (Blueprint $table) {
            // remains_en の後ろ（= created_at の前側）に追加
            $table->text('opening_hours_ja')->nullable()->after('remains_en');
            $table->text('opening_hours_en')->nullable()->after('opening_hours_ja');

            $table->string('closed_days_ja', 255)->nullable()->after('opening_hours_en');
            $table->string('closed_days_en', 255)->nullable()->after('closed_days_ja');

            $table->string('admission_fee_ja', 255)->nullable()->after('closed_days_en');
            $table->string('admission_fee_en', 255)->nullable()->after('admission_fee_ja');
        });
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn([
                'opening_hours_ja','opening_hours_en',
                'closed_days_ja','closed_days_en',
                'admission_fee_ja','admission_fee_en',
            ]);
        });
    }
};
