<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['type' => 'castle', 'name_ja' => '日本100名城',   'name_en' => 'Japan 100 Famous Castles', 'slug' => 'japan-100-castles',        'sort_order' => 1],
            ['type' => 'castle', 'name_ja' => '続日本100名城', 'name_en' => 'Japan 100 Continued Castles', 'slug' => 'continued-100-castles', 'sort_order' => 2],
            ['type' => 'castle', 'name_ja' => 'それ以外の城',   'name_en' => 'Other Castles', 'slug' => 'other-castles', 'sort_order' => 3],
            ['type' => 'cultural_property', 'name_ja' => '文化財', 'name_en' => 'Cultural Properties', 'slug' => 'cultural-properties', 'sort_order' => 4],
        ];

        foreach ($rows as $row) {
            Category::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
