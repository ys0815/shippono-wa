<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShelterSeeder extends Seeder
{
    public function run(): void
    {
        $shelters = [
            // 北海道・東北 (hokkaido_tohoku)
            ['name' => 'NPO法人 Nyapan Cat Rescue', 'area' => 'hokkaido_tohoku', 'kind' => 'facility', 'prefecture' => '北海道', 'website_url' => 'https://nyapan.jp/'],
            ['name' => '北海道動物愛護センターあいにきた', 'area' => 'hokkaido_tohoku', 'kind' => 'facility', 'prefecture' => '北海道', 'website_url' => 'https://doto-aigo.com/'],
            ['name' => '北海道立動物愛護センター', 'area' => 'hokkaido_tohoku', 'kind' => 'facility', 'prefecture' => '北海道', 'website_url' => 'https://www.pref.hokkaido.lg.jp/ks/awc/ainikita.html'],
            ['name' => '動物いのちの会いわて', 'area' => 'hokkaido_tohoku', 'kind' => 'facility', 'prefecture' => '岩手県', 'website_url' => 'https://inochiiwate.com/'],
            ['name' => '宮城県動物愛護センター', 'area' => 'hokkaido_tohoku', 'kind' => 'facility', 'prefecture' => '宮城県', 'website_url' => 'https://www.pref.miyagi.jp/soshiki/doubutuaigo/'],
            ['name' => '動物管理センター アニパル仙台', 'area' => 'hokkaido_tohoku', 'kind' => 'facility', 'prefecture' => '宮城県', 'website_url' => 'https://www.city.sendai.jp/kurashi/shizen/petto/hogodobutsu/aigo/index.html'],
            ['name' => '庄内アニマル倶楽部', 'area' => 'hokkaido_tohoku', 'kind' => 'facility', 'prefecture' => '山形県', 'website_url' => 'https://shonaianimal.club/'],

            // 関東 (kanto)
            ['name' => 'NPO法人 Delacroix Dog Ranch', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '群馬県', 'website_url' => 'https://ddranch.jp/'],
            ['name' => 'NPO法人ペット里親会', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '埼玉県', 'website_url' => 'https://www.petsatooyakai.com/'],
            ['name' => 'わんにゃん小梅保育園FARCO', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '埼玉県', 'website_url' => 'https://chibawan.net/'],
            ['name' => 'さいたま市動物愛護ふれあいセンター', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '埼玉県', 'website_url' => 'https://www.city.saitama.lg.jp/008/004/003/index.html'],
            ['name' => 'ちばわん', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '千葉県', 'website_url' => 'https://chibawan.net/'],
            ['name' => 'Life boat', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '千葉県', 'website_url' => 'https://www.lifeboat.or.jp/'],
            ['name' => 'みなとねこ', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '東京都', 'website_url' => 'https://www.minatoneco.com/'],
            ['name' => 'ゆめネコ譲渡会', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '東京都', 'website_url' => 'http://yume-neko.net/'],
            ['name' => '東京キャットガーディアン', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '東京都', 'website_url' => 'https://tokyocatguardian.org/'],
            ['name' => 'SMILE CAT', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '東京都', 'website_url' => 'https://smilecat.jp/'],
            ['name' => 'NPO法人にゃいるどはーと', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '東京都', 'website_url' => 'https://nyaild-heart.com/'],
            ['name' => 'NPO法人 Link to', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '東京都', 'website_url' => 'https://linkto-or.com/'],
            ['name' => '特定非営利活動法人アルマ', 'area' => 'kanto', 'kind' => 'facility', 'prefecture' => '東京都', 'website_url' => 'https://alma.or.jp/'],

            // 中部・東海 (chubu_tokai)
            ['name' => '富山県動物管理センター', 'area' => 'chubu_tokai', 'kind' => 'facility', 'prefecture' => '富山県', 'website_url' => 'https://www.pref.toyama.jp/1207/kurashi/seikatsu/seikatsu/doubutsuaigo/about/index.html'],
            ['name' => '山梨県動物愛護指導センター', 'area' => 'chubu_tokai', 'kind' => 'facility', 'prefecture' => '山梨県', 'website_url' => 'https://www.pref.yamanashi.jp/doubutsu/'],
            ['name' => '名古屋市 人とペットの共生サポートセンター', 'area' => 'chubu_tokai', 'kind' => 'facility', 'prefecture' => '愛知県', 'website_url' => 'https://dog-cat-support.nagoya/'],
            ['name' => 'わんにゃんさとおや会', 'area' => 'chubu_tokai', 'kind' => 'facility', 'prefecture' => '愛知県', 'website_url' => 'https://www.satooyakai.or.jp/'],
            ['name' => '三重県動物愛護推進センターあすまいる', 'area' => 'chubu_tokai', 'kind' => 'facility', 'prefecture' => '三重県', 'website_url' => 'https://www.pref.mie.lg.jp/ASMILE/'],

            // 近畿 (kinki)
            ['name' => '京都動物愛護センター', 'area' => 'kinki', 'kind' => 'facility', 'prefecture' => '京都府', 'website_url' => 'https://kyoto-ani-love.com/'],
            ['name' => '大阪府動物愛護管理センター', 'area' => 'kinki', 'kind' => 'facility', 'prefecture' => '大阪府', 'website_url' => 'https://www.pref.osaka.lg.jp/o120200/doaicenter/doaicenter/index.html'],
            ['name' => '保護ねこの家', 'area' => 'kinki', 'kind' => 'facility', 'prefecture' => '大阪府', 'website_url' => 'https://www.hogonekonoie.jp/'],
            ['name' => '兵庫県動物愛護センター', 'area' => 'kinki', 'kind' => 'facility', 'prefecture' => '兵庫県', 'website_url' => 'https://hyogo-douai.sakura.ne.jp/'],
            ['name' => '中和保健所動物愛護センター', 'area' => 'kinki', 'kind' => 'facility', 'prefecture' => '奈良県', 'website_url' => 'https://www.pref.nara.jp/1734.htm'],
            ['name' => '和歌山県動物愛護センター', 'area' => 'kinki', 'kind' => 'facility', 'prefecture' => '和歌山県', 'website_url' => 'https://www.pref.wakayama.lg.jp/prefg/031601/animal.html'],

            // 中国・四国 (chugoku_shikoku)
            ['name' => '山口県動物愛護センター', 'area' => 'chugoku_shikoku', 'kind' => 'facility', 'prefecture' => '山口県', 'website_url' => 'https://www.pref.yamaguchi.lg.jp/site/doubutuaigo/'],
            ['name' => 'さぬき動物愛護センター しっぽの森', 'area' => 'chugoku_shikoku', 'kind' => 'facility', 'prefecture' => '香川県', 'website_url' => 'https://www.pref.kagawa.lg.jp/s-doubutuaigo/sanukidouaicenter/index.html'],
            ['name' => '愛媛県動物愛護センター', 'area' => 'chugoku_shikoku', 'kind' => 'facility', 'prefecture' => '愛媛県', 'website_url' => 'https://www.pref.ehime.jp/page/16944.html'],

            // 九州・沖縄 (kyushu_okinawa)
            ['name' => '北九州市動物愛護センター', 'area' => 'kyushu_okinawa', 'kind' => 'facility', 'prefecture' => '福岡県', 'website_url' => 'https://www.city.kitakyushu.lg.jp/contents/division185.html'],
            ['name' => '佐賀県犬猫譲渡センター「いっしょけんね」', 'area' => 'kyushu_okinawa', 'kind' => 'facility', 'prefecture' => '佐賀県', 'website_url' => 'https://www.pref.saga.lg.jp/kiji00315040/index.html'],
            ['name' => 'おおいた動物愛護センター', 'area' => 'kyushu_okinawa', 'kind' => 'facility', 'prefecture' => '大分県', 'website_url' => 'https://oita-aigo.com/'],
            ['name' => 'みやざき動物愛護センター', 'area' => 'kyushu_okinawa', 'kind' => 'facility', 'prefecture' => '宮崎県', 'website_url' => 'https://dog.pref.miyazaki.lg.jp/'],
            ['name' => '鹿児島県動物愛護センター', 'area' => 'kyushu_okinawa', 'kind' => 'facility', 'prefecture' => '鹿児島県', 'website_url' => 'http://dogcat.pref.kagoshima.jp/'],
            ['name' => '沖縄県動物愛護管理センター', 'area' => 'kyushu_okinawa', 'kind' => 'facility', 'prefecture' => '沖縄県', 'website_url' => 'https://www.aniwel-pref.okinawa/'],

            // 全国 (national)
            ['name' => 'ピースワンコ・ジャパン', 'area' => 'national', 'kind' => 'facility', 'prefecture' => null, 'website_url' => 'https://wanko.peace-winds.org/'],

            // 里親サイト (national)
            ['name' => '東京都動物情報サイト ワンニャンとうきょう', 'area' => 'national', 'kind' => 'site', 'prefecture' => null, 'website_url' => 'https://wannyan.metro.tokyo.lg.jp/'],
            ['name' => 'ペットのおうち', 'area' => 'national', 'kind' => 'site', 'prefecture' => null, 'website_url' => 'https://www.pet-home.jp/'],
            ['name' => 'OMUSUBI', 'area' => 'national', 'kind' => 'site', 'prefecture' => null, 'website_url' => 'https://omusubi-pet.com/'],
            ['name' => 'ハグー', 'area' => 'national', 'kind' => 'site', 'prefecture' => null, 'website_url' => 'https://hug-u.pet/'],
            ['name' => 'いつでも里親募集中', 'area' => 'national', 'kind' => 'site', 'prefecture' => null, 'website_url' => 'https://satoya-boshu.net/'],
            ['name' => 'ネコジルシ', 'area' => 'national', 'kind' => 'site', 'prefecture' => null, 'website_url' => 'https://www.neko-jirushi.com/'],
        ];

        // name, prefecture から prefecture_id を解決
        $prefMap = DB::table('prefectures')->pluck('id', 'name');

        foreach ($shelters as $shelter) {
            DB::table('shelters')->insert([
                'name' => $shelter['name'],
                'area' => $shelter['area'],
                'kind' => $shelter['kind'],
                'prefecture_id' => $shelter['prefecture'] ? ($prefMap[$shelter['prefecture']] ?? null) : null,
                'website_url' => $shelter['website_url'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
