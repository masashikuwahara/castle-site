<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name_ja'=>'山城','name_en'=>'Mountain castle','slug'=>'yamajiro'],
            ['name_ja'=>'平山城','name_en'=>'Hilltop castle','slug'=>'hirayamajiro'],
            ['name_ja'=>'平城','name_en'=>'Flatland castle','slug'=>'hirajiro'],
            ['name_ja'=>'現存天守','name_en'=>'Original keep','slug'=>'original-tenshu'],
            ['name_ja'=>'石垣','name_en'=>'Stone walls','slug'=>'ishigaki'],
            ['name_ja'=>'堀','name_en'=>'Moat','slug'=>'hori'],
        ];

        foreach ($rows as $row) {
            Tag::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
