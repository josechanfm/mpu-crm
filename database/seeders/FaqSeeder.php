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
            'department_id'=>1,
            'category_id' => 1,
            'subjects'=>'["ADM"]',
            'question_zh'=>'澳門理工大學提供哪些課程?',
            'answer_zh'=>'本校提供<a href="https://www.mpu.edu.mo/admission_local/zh/postgraduate.php" target="_blank">「研究生課程」</a>及
            <a href="https://www.mpu.edu.mo/admission_local/zh/undergraduate.php" target="_blank">「學士學位課程」</a>
            （範疇包括：工商管理、資訊科技、語言與翻譯、公共管理與服務、藝術、健康科學及體育）。'
        ]);
        DB::table('faqs')->insert([
            'department_id'=>1,
            'category_id' => 1,
            'subjects'=>'["ADM"]',
            'question_zh'=>'課程採用甚麼學制?',
            'answer_zh'=>'博士學位課程修讀期為三年、碩士學位課程修讀期為兩年；<br>學士學位課程均為四年制。'
        ]);
        DB::table('faqs')->insert([
            'department_id'=>1,
            'category_id' => 1,
            'subjects'=>'["ADM"]',
            'question_zh'=>'課程採用甚麼語言授課?',
            'answer_zh'=>'各課程採用不同的授課語言。有關課程之授課語言可於「研究生課程」及「學士學位課程」中瀏覽。'
        ]);
        DB::table('faqs')->insert([
            'department_id'=>1,
            'category_id' => 1,
            'subjects'=>'["ADM","PRO"]',
            'question_zh'=>'課程是否限制文科或理科組別學生報考?',
            'answer_zh'=>'本校文理兼收，任何組別學生皆可報考本校提供的課程。'
        ]);
        DB::table('faqs')->insert([
            'department_id'=>1,
            'category_id' => 2,
            'subjects'=>'["PRO"]',
            'question_zh'=>'	
            報考研究生課程須具備哪些條件?',
            'answer_zh'=>'博士學位課程：<br>
            具碩士學位或同等學歷；或正修讀碩士學位學歷課程最後一年；及
            尚須具備報讀課程要求的其他報考條件。<br>
            碩士學位課程：
            具學士學位或同等學歷；或正修讀學士學位學歷課程最後一年；及
            尚須具備報讀課程要求的其他報考條件。'
        ]);

    }
}
