<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>0,
            'root_id'=>1,
            'title'=>'哪些課程',
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>'澳門理工大學提供哪些課程?',
            'admin_user_id'=>1,
        ]);
        $id=DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>0,
            'root_id'=>2,
            'title'=>'學制',
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>'課程採用甚麼學制?',
            'admin_user_id'=>1,
        ]);
        DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>2,
            'root_id'=>2,
            'title'=>'學制 follow 1',
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>'課程採用甚麼學制? follow up 1',
            'admin_user_id'=>1,
        ]);
        $id=DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>2,
            'root_id'=>2,
            'title'=>'學制 follow 2',
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>'課程採用甚麼學制? follow up 2',
            'admin_user_id'=>1,
        ]);
        $id=DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>0,
            'root_id'=>5,
            'title'=>'授課語言',
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>'課程採用甚麼語言授課?',
            'admin_user_id'=>1,
        ]);
        $id=DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>5,
            'root_id'=>5,
            'title'=>'授課語言 follow up 1',
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>'課程採用甚麼語言授課? follow up 1',
            'admin_user_id'=>1,
        ]);
        DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>0,
            'root_id'=>6,
            'title'=>'課程限制',
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>'課程是否限制文科或理科組別學生報考?',
            'content'=>$faker->text,
            'admin_user_id'=>1,
        ]);

        
    }
}
