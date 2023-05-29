<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faqs')->insert([
            'caregory_id' => '1',
            'question_zh'=>'澳門理工大學提供哪些課程?',
            'answer_zh'=>'本校提供<a href="https://www.mpu.edu.mo/admission_local/zh/postgraduate.php" target="_blank">「研究生課程」</a>及<a href="https://www.mpu.edu.mo/admission_local/zh/undergraduate.php" target="_blank">「學士學位課程」（範疇包括：工商管理、資訊科技、語言與翻譯、公共管理與服務、藝術、健康科學及體育）。'
        ]);
        DB::table('faqs')->insert([
            'caregory_id' => '1',
            'question_zh'=>'課程採用甚麼學制?',
            'answer_zh'=>'博士學位課程修讀期為三年、碩士學位課程修讀期為兩年；<br>學士學位課程均為四年制。'
        ]);
        DB::table('faqs')->insert([
            'caregory_id' => '1',
            'question_zh'=>'',
            'answer_zh'=>''
        ]);
        DB::table('faqs')->insert([
            'caregory_id' => '1',
            'question_zh'=>'',
            'answer_zh'=>''
        ]);
        DB::table('faqs')->insert([
            'caregory_id' => '1',
            'question_zh'=>'',
            'answer_zh'=>''
        ]);

    }
}
