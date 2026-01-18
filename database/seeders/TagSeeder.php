<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // ['name_ja'=>'山城','name_en'=>'Mountain castle','slug'=>'yamajiro'],
            // ['name_ja'=>'平山城','name_en'=>'Hilltop castle','slug'=>'hirayamajiro'],
            // ['name_ja'=>'平城','name_en'=>'Flatland castle','slug'=>'hirajiro'],
            // ['name_ja'=>'現存天守','name_en'=>'Original keep','slug'=>'original-tenshu'],
            // ['name_ja'=>'石垣','name_en'=>'Stone walls','slug'=>'ishigaki'],
            // ['name_ja'=>'堀','name_en'=>'Moat','slug'=>'hori'],
            // ['name_ja'=>'海城','name_en'=>'Sea castle','slug'=>'umijiro'],
            // ['name_ja'=>'空堀','name_en'=>'Dry moat','slug'=>'karabori'],
            // ['name_ja'=>'水堀','name_en'=>'Water moat','slug'=>'mizubori'],
            // ['name_ja'=>'櫓','name_en'=>'Turret','slug'=>'yagura'],
            // ['name_ja'=>'天守台','name_en'=>'Keep base','slug'=>'tenshudai'],
            // ['name_ja'=>'城門','name_en'=>'Castle gate','slug'=>'castle-gate'],
            // ['name_ja'=>'駅近','name_en'=>'Near station','slug'=>'near-station'],
            // ['name_ja'=>'登山/ハイキング','name_en'=>'Hiking','slug'=>'hiking'],
            // ['name_ja'=>'国宝','name_en'=>'National Treasure','slug'=>'national-treasure'],
            // ['name_ja'=>'世界遺産','name_en'=>'World Heritage','slug'=>'world-heritage'],
            // ['name_ja'=>'復元天守','name_en'=>'Reconstructed keep','slug'=>'reconstructed-tenshu'],
            // ['name_ja'=>'模擬天守','name_en'=>'Replica keep','slug'=>'replica-tenshu'],
            // ['name_ja'=>'重要文化財','name_en'=>'Important Cultural Property','slug'=>'important-cultural-property'],
            // ['name_ja'=>'国史跡','name_en'=>'National Historic Site','slug'=>'national-historic-site'],
            ['name_ja'=>'土塁','name_en'=>'Earthworks','slug'=>'dorui'],
            ['name_ja'=>'御殿','name_en'=>'Palace','slug'=>'goten'],
        ];

        foreach ($rows as $row) {
            Tag::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
