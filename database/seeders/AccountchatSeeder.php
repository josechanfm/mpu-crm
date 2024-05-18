<?php

namespace Database\Seeders;

use App\Models\Accountchart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountchatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            ['parent_id'=>0,'code'=>'1','title_zh'=>'資產'],
            ['parent_id'=>1,'code'=>'11','title_zh'=>'流動資產'],
            ['parent_id'=>2,'code'=>'111','title_zh'=>'現金及約當現金'],
            ['parent_id'=>3,'code'=>'1111','title_zh'=>'庫存現金'],
            ['parent_id'=>3,'code'=>'1112','title_zh'=>'零用金／週轉金'],
            ['parent_id'=>3,'code'=>'1113','title_zh'=>'銀行存款'],
            ['parent_id'=>3,'code'=>'1114','title_zh'=>'在途現金'],
            ['parent_id'=>3,'code'=>'1115','title_zh'=>'約當現金'],
            ['parent_id'=>2,'code'=>'112','title_zh'=>'透過損益按公允價值衡量之金融資產—流動'],
            ['parent_id'=>9,'code'=>'1121','title_zh'=>'指定為透過損益按公允價值衡量之金融資產—流動'],
            ['parent_id'=>9,'code'=>'1122','title_zh'=>'指定為透過損益按公允價值衡量之金融資產評價調整—流動'],
            ['parent_id'=>0,'code'=>'2','title_zh'=>'負責'],
            ['parent_id'=>12,'code'=>'21','title_zh'=>'流動負債'],
            ['parent_id'=>13,'code'=>'211','title_zh'=>'短期借款'],
            ['parent_id'=>14,'code'=>'2111','title_zh'=>'銀行透支'],
            ['parent_id'=>14,'code'=>'2112','title_zh'=>'銀行借款'],
            ['parent_id'=>14,'code'=>'2113','title_zh'=>'短期借款—業主'],
            ['parent_id'=>14,'code'=>'2114','title_zh'=>'短期借款—員工'],
            ['parent_id'=>14,'code'=>'2115','title_zh'=>'短期借款—關係人'],
            ['parent_id'=>14,'code'=>'2116','title_zh'=>'短期借款—其他'],
            ['parent_id'=>12,'code'=>'212','title_zh'=>'付短期票券'],
            ['parent_id'=>21,'code'=>'2121','title_zh'=>'應付商業本票'],
            ['parent_id'=>21,'code'=>'2122','title_zh'=>'銀行承兌匯票'],
            ['parent_id'=>21,'code'=>'2123','title_zh'=>' 其他應付短期票券'],
            ['parent_id'=>21,'code'=>'2124','title_zh'=>'應付短期票券折價'],
        ];
        foreach($data as $d){
            Accountchart::create($d);
        }
    }
}
