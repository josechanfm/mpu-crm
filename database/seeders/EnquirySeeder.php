<?php

namespace Database\Seeders;

use App\Models\Enquiry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EnquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enquiries')->insert([
            'department_id'=>1,
            'lang'=>'zh',
            'origin'=>'MO',
            'degree'=>'B',
            'admission'=>'EXAM',
            'profile'=>'STD',
            'apply'=>false,
            'surname'=>'surname',
            'givenname'=>'givenname',
            'email'=>'example@example.com',
            'areacode'=>'853',
            'phone'=>'123456',
            'subjects'=>'["ADM"]',
            'token'=>hash('crc32',time().'1'),
            'has_question'=>false,
        ]);
        DB::table('enquiry_questions')->insert([
            'enquiry_id'=>1,
            'parent_id'=>NULL,
            'content'=>'my question 1 is ...',
            'token'=>'token001',
            'is_closed'=>0
        ]);
        DB::table('enquiry_responses')->insert([
            'enquiry_question_id'=>1,
            'title'=>'question title',
            'remark'=>'remark of my question',
            'by_email'=>true,
            'email_address'=>'mail address',
            'email_subject'=>'email subject',
            'email_content'=>'email content',
            'admin_id'=>1,
            'token'=>hash('crc32',time().'1'),
        ]);

        DB::table('enquiries')->insert([
            'department_id'=>1,
            'lang'=>'zh',
            'origin'=>'MO',
            'degree'=>'B',
            'admission'=>'EXAM',
            'profile'=>'STD',
            'apply'=>false,
            'surname'=>'surname2',
            'givenname'=>'givenname2',
            'email'=>'example@example.com2',
            'areacode'=>'85322',
            'phone'=>'12345622',
            'subjects'=>'["ADM"]',
            'token'=>hash('crc32',time().'1'),
            'has_question'=>false,
        ]);
        DB::table('enquiry_questions')->insert([
            'enquiry_id'=>2,
            'parent_id'=>NULL,
            'content'=>'my question 2 is ...',
            'token'=>'token002',
            'is_closed'=>0
        ]);
        DB::table('enquiry_responses')->insert([
            'enquiry_question_id'=>2,
            'title'=>'question title',
            'remark'=>'remark of my question',
            'by_email'=>true,
            'email_address'=>'mail address',
            'email_subject'=>'email subject',
            'email_content'=>'email content',
            'admin_id'=>1,
            'token'=>hash('crc32',time().'1'),
        ]);
        
    }
}
