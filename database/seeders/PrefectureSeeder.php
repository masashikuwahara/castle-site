<?php

namespace Database\Seeders;

use App\Models\Prefecture;
use Illuminate\Database\Seeder;

class PrefectureSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name_ja'=>'北海道','name_en'=>'Hokkaido','slug'=>'hokkaido'],
            ['name_ja'=>'青森県','name_en'=>'Aomori','slug'=>'aomori'],
            ['name_ja'=>'岩手県','name_en'=>'Iwate','slug'=>'iwate'],
            ['name_ja'=>'宮城県','name_en'=>'Miyagi','slug'=>'miyagi'],
            ['name_ja'=>'秋田県','name_en'=>'Akita','slug'=>'akita'],
            ['name_ja'=>'山形県','name_en'=>'Yamagata','slug'=>'yamagata'],
            ['name_ja'=>'福島県','name_en'=>'Fukushima','slug'=>'fukushima'],

            ['name_ja'=>'茨城県','name_en'=>'Ibaraki','slug'=>'ibaraki'],
            ['name_ja'=>'栃木県','name_en'=>'Tochigi','slug'=>'tochigi'],
            ['name_ja'=>'群馬県','name_en'=>'Gunma','slug'=>'gunma'],
            ['name_ja'=>'埼玉県','name_en'=>'Saitama','slug'=>'saitama'],
            ['name_ja'=>'千葉県','name_en'=>'Chiba','slug'=>'chiba'],
            ['name_ja'=>'東京都','name_en'=>'Tokyo','slug'=>'tokyo'],
            ['name_ja'=>'神奈川県','name_en'=>'Kanagawa','slug'=>'kanagawa'],

            ['name_ja'=>'新潟県','name_en'=>'Niigata','slug'=>'niigata'],
            ['name_ja'=>'富山県','name_en'=>'Toyama','slug'=>'toyama'],
            ['name_ja'=>'石川県','name_en'=>'Ishikawa','slug'=>'ishikawa'],
            ['name_ja'=>'福井県','name_en'=>'Fukui','slug'=>'fukui'],
            ['name_ja'=>'山梨県','name_en'=>'Yamanashi','slug'=>'yamanashi'],
            ['name_ja'=>'長野県','name_en'=>'Nagano','slug'=>'nagano'],

            ['name_ja'=>'岐阜県','name_en'=>'Gifu','slug'=>'gifu'],
            ['name_ja'=>'静岡県','name_en'=>'Shizuoka','slug'=>'shizuoka'],
            ['name_ja'=>'愛知県','name_en'=>'Aichi','slug'=>'aichi'],
            ['name_ja'=>'三重県','name_en'=>'Mie','slug'=>'mie'],

            ['name_ja'=>'滋賀県','name_en'=>'Shiga','slug'=>'shiga'],
            ['name_ja'=>'京都府','name_en'=>'Kyoto','slug'=>'kyoto'],
            ['name_ja'=>'大阪府','name_en'=>'Osaka','slug'=>'osaka'],
            ['name_ja'=>'兵庫県','name_en'=>'Hyogo','slug'=>'hyogo'],
            ['name_ja'=>'奈良県','name_en'=>'Nara','slug'=>'nara'],
            ['name_ja'=>'和歌山県','name_en'=>'Wakayama','slug'=>'wakayama'],

            ['name_ja'=>'鳥取県','name_en'=>'Tottori','slug'=>'tottori'],
            ['name_ja'=>'島根県','name_en'=>'Shimane','slug'=>'shimane'],
            ['name_ja'=>'岡山県','name_en'=>'Okayama','slug'=>'okayama'],
            ['name_ja'=>'広島県','name_en'=>'Hiroshima','slug'=>'hiroshima'],
            ['name_ja'=>'山口県','name_en'=>'Yamaguchi','slug'=>'yamaguchi'],

            ['name_ja'=>'徳島県','name_en'=>'Tokushima','slug'=>'tokushima'],
            ['name_ja'=>'香川県','name_en'=>'Kagawa','slug'=>'kagawa'],
            ['name_ja'=>'愛媛県','name_en'=>'Ehime','slug'=>'ehime'],
            ['name_ja'=>'高知県','name_en'=>'Kochi','slug'=>'kochi'],

            ['name_ja'=>'福岡県','name_en'=>'Fukuoka','slug'=>'fukuoka'],
            ['name_ja'=>'佐賀県','name_en'=>'Saga','slug'=>'saga'],
            ['name_ja'=>'長崎県','name_en'=>'Nagasaki','slug'=>'nagasaki'],
            ['name_ja'=>'熊本県','name_en'=>'Kumamoto','slug'=>'kumamoto'],
            ['name_ja'=>'大分県','name_en'=>'Oita','slug'=>'oita'],
            ['name_ja'=>'宮崎県','name_en'=>'Miyazaki','slug'=>'miyazaki'],
            ['name_ja'=>'鹿児島県','name_en'=>'Kagoshima','slug'=>'kagoshima'],
            ['name_ja'=>'沖縄県','name_en'=>'Okinawa','slug'=>'okinawa'],
        ];

        foreach ($rows as $row) {
            Prefecture::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
