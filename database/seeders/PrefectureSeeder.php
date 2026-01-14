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

        $coords = [
            'hokkaido' => [43.064167, 141.346667],
            'aomori' => [40.824167, 140.740000],
            'iwate' => [39.703333, 141.152500],
            'miyagi' => [38.268889, 140.871944],
            'akita' => [39.718611, 140.102222],
            'yamagata' => [38.240278, 140.363333],
            'fukushima' => [37.750000, 140.467500],

            'ibaraki' => [36.341667, 140.446667],
            'tochigi' => [36.565556, 139.883611],
            'gunma' => [36.391111, 139.060833],
            'saitama' => [35.856944, 139.648889],
            'chiba' => [35.604444, 140.123056],
            'tokyo' => [35.689167, 139.691667],
            'kanagawa' => [35.447778, 139.642500],

            'niigata' => [37.902222, 139.023056],
            'toyama' => [36.695000, 137.211111],
            'ishikawa' => [36.594444, 136.625556],
            'fukui' => [36.065000, 136.221667],
            'yamanashi' => [35.663889, 138.568333],
            'nagano' => [36.651111, 138.180833],

            'gifu' => [35.390833, 136.721944],
            'shizuoka' => [34.976667, 138.383056],
            'aichi' => [35.180278, 136.906667],
            'mie' => [34.730278, 136.508333],

            'shiga' => [35.004167, 135.868056],
            'kyoto' => [35.021111, 135.755556],
            'osaka' => [34.686389, 135.520000],
            'hyogo' => [34.691111, 135.183056],
            'nara' => [34.685278, 135.832778],
            'wakayama' => [34.225833, 135.167222],

            'tottori' => [35.503333, 134.238056],
            'shimane' => [35.472222, 133.050278],
            'okayama' => [34.661667, 133.935000],
            'hiroshima' => [34.396389, 132.459444],
            'yamaguchi' => [34.185556, 131.471389],

            'tokushima' => [34.065556, 134.559167],
            'kagawa' => [34.340000, 134.043056],
            'ehime' => [33.841667, 132.765833],
            'kochi' => [33.559444, 133.530833],

            'fukuoka' => [33.606389, 130.418056],
            'saga' => [33.249167, 130.298611],
            'nagasaki' => [32.750000, 129.867222],
            'kumamoto' => [32.789444, 130.741667],
            'oita' => [33.238056, 131.612500],
            'miyazaki' => [31.910833, 131.423889],
            'kagoshima' => [31.560278, 130.558056],
            'okinawa' => [26.212222, 127.680833],
        ];

        foreach ($rows as $row) {
            $slug = $row['slug'];

            // 追加：lat/lng を追記（無い県は null のまま）
            $row['lat'] = $coords[$slug][0] ?? null;
            $row['lng'] = $coords[$slug][1] ?? null;

            Prefecture::updateOrCreate(['slug' => $slug], $row);
        }
    }
}
