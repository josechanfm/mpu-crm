<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'origin',
            'value'=>'{
                "question":"擬入讀本校之學生現時所持有之證件 Type of personal Identification document held by the candidate",
                "short":"持有證件",
                "options":[
                    {"value":"MO","label":"澳門居民身份證 MACAU ID"},
                    {"value":"CN","label":"中華人民共和國居民身份證 CHINA ID"},
                    {"value":"HK","label":"香港居民身份證 HONG KONG ID"},
                    {"value":"TW","label":"台灣居民身份證 TAIWAN ID"},
                    {"value":"FR","label":"外國護照 (非持有上述證件的外國居民) PASSPORT"}
                ]
            }'
        ]);

        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'degree',
            'value'=>'{
                "question":"入讀課程類別 Apply programme",
                "short":"入讀課程",
                "options":[
                    {"value":"B","label":"學士學位 本科 Bachelor"},
                    {"value":"M","label":"碩士學位 Master"},
                    {"value":"D","label":"博士學位 Doctoral"}
                ]
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'admission',
            'value'=>'{
                "question":"入學途徑 Admission route",
                "short":"入學途徑",
                "options":[
                    {"value":"EXAM","label":"入學考試 Admission exams"},
                    {"value":"RECOMMEND","label":"澳門中學校長推薦新生入學計劃 Principals’ recommendation"},
                    {"value":"TELENT","label":"專才入學計劃 Local talents and professionals"},
                    {"value":"DIRECT","label":"直接入學 Direct admission"},
                    {"value":"OTHER","label":"其他 Others (現就讀於高等院校學士課程之學生適用 Applicable to students who are currently studying bachelor programs in higher education institutions)"},
                    {"value":"GAOKAO","label":"高考生 Mainland China GAOKAO students"},
                    {"value":"DIRECT","label":"直接入學 Direct admission (非高考生持國際課程或公開考試成績的內地籍高中應屆畢業生 Non-GAOKAO student and Fresh graduates from high schools who hold international curriculum or public exam result)"},
                    {"value":"EXAM","label":"入學考試 Admission exams"},
                    {"value":"DIRECT","label":"直接入學 Direct admission"}
                ]
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'profile',
            'value'=>'{
                "question":"簡介 Profiles",
                "short":"簡介",
                "options":[
                    {"value":"STD","label":"學生 Student"},
                    {"value":"PAR","label":"家長 Parent"},
                    {"value":"TEA","label":"老師 Teacher"},
                    {"value":"EDU","label":"教育機構/顧問 Education centre/counsellor"},
                    {"value":"OTH","label":"其他 Other"}
                ]
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'apply',
            'value'=>'{
                "question":"是否已報讀澳理大學位課程? Have you submitted an application for admission in current year?",
                "short":"已報讀",
                "options":[
                    {"value":false,"label":"否 No"},
                    {"value":true,"label":"是 Yes"}
                ]
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'surname',
            'value'=>'{
                "question":"姓 Surname",
                "short":"姓"
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'givenname',
            'value'=>'{
                "question":"名 Given Name",
                "short":"名"
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'email',
            'value'=>'{
                "question":"電郵 Email",
                "short":"電郵"
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'contact_number',
            'value'=>'{
                "question": "聯絡電話 phone number",
                "short":"聯絡電話"
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'areacode',
            'value'=>'{
                "question":"區號 Area code",
                "short":"區號"
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'phone',
            'value'=>'{
                "question":"電話 Phone number",
                "short":"電話"
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'subjects',
            'value'=>'{
                "question":"主旨 Subject",
                "short":"主旨",
                "options":[
                    {"value":"ADM","label":"入學要求 Admission Requirements"},
                    {"value":"PRO","label":"課程資訊 Programme Information"},
                    {"value":"APP","label":"報名資訊 Application Procedures"},
                    {"value":"FEE","label":"學費及奬學金 Fees and Scholarships"},
                    {"value":"RES","label":"錄取資訊 Admission Result"},
                    {"value":"UPD","label":"更新報名資訊 Updating Information on Application Form"},
                    {"value":"PAY","label":"繳費事宜 Payment Issue"},
                    {"value":"REG","label":"入學登記手續、體檢 Student registration and physical examination"},
                    {"value":"OTH","label":"其他 others"}
                ]
            }'
        ]);
    
        DB::table('configs')->insert([
            'division'=>'enquiry_form',
            'label'=>'agree',
            'value'=>'{
                "question":"本人同意澳門理工大學就招生資訊或活動發送電郵或短訊予本人。<br>I give my consent to Macau Polytechnic University for send me emails or SMS regarding admissions information or activities.",
                "short":"同意"
            }'
        ]);
        DB::table('configs')->insert([
            'division'=>'phone_country_codes',
            'label'=>'phone_country_code',
            'value'=>'
            [
                {
                  "countryCode": "86",
                  "labelZh": "中國",
                  "labelEn": "China"
                },{
                  "countryCode": "853",
                  "labelZh": "澳門",
                  "labelEn": "Macao"
                },{
                  "countryCode": "852",
                  "labelZh": "香港",
                  "labelEn": "Hong Kong"
                },{
                  "countryCode": "886",
                  "labelZh": "台灣",
                  "labelEn": "Taiwan"
                },{
                  "countryCode": "1",
                  "labelZh": "美國",
                  "labelEn": "United States"
                },{
                  "countryCode": "1",
                  "labelZh": "加拿大",
                  "labelEn": "Canada"
                },{
                  "countryCode": "44",
                  "labelZh": "英國",
                  "labelEn": "United Kingdom"
                },{
                  "countryCode": "61",
                  "labelZh": "澳大利亞",
                  "labelEn": "Australia"
                },{
                  "countryCode": "91",
                  "labelZh": "印度",
                  "labelEn": "India"
                },{
                  "countryCode": "81",
                  "labelZh": "日本",
                  "labelEn": "Japan"
                },{
                  "countryCode": "49",
                  "labelZh": "德國",
                  "labelEn": "Germany"
                },{
                  "countryCode": "33",
                  "labelZh": "法國",
                  "labelEn": "France"
                },{
                  "countryCode": "55",
                  "labelZh": "巴西",
                  "labelEn": "Brazil"
                },{
                  "countryCode": "52",
                  "labelZh": "墨西哥",
                  "labelEn": "Mexico"
                },{
                  "countryCode": "27",
                  "labelZh": "南非",
                  "labelEn": "South Africa"
                },{
                  "countryCode": "7",
                  "labelZh": "俄羅斯",
                  "labelEn": "Russia"
                },{
                  "countryCode": "34",
                  "labelZh": "西班牙",
                  "labelEn": "Spain"
                },{
                  "countryCode": "39",
                  "labelZh": "意大利",
                  "labelEn": "Italy"
                },{
                  "countryCode": "31",
                  "labelZh": "荷蘭",
                  "labelEn": "Netherlands"
                },{
                  "countryCode": "65",
                  "labelZh": "新加坡",
                  "labelEn": "Singapore"
                },{
                  "countryCode": "82",
                  "labelZh": "韓國",
                  "labelEn": "South Korea"
                },{
                  "countryCode": "966",
                  "labelZh": "沙特阿拉伯",
                  "labelEn": "Saudi Arabia"
                },{
                  "countryCode": "971",
                  "labelZh": "阿拉伯聯合酋長國",
                  "labelEn": "United Arab Emirates"
                }
              ]'
        ]);

    }
}
