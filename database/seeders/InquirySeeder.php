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
            'title'=>$faker->text,
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>$faker->text,
            'admin_user_id'=>1,
        ]);
        $id=DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>0,
            'root_id'=>2,
            'title'=>$faker->text,
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>$faker->text,
            'admin_user_id'=>1,
        ]);
        DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>2,
            'root_id'=>2,
            'title'=>$faker->text,
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>$faker->text,
            'admin_user_id'=>1,
        ]);
        $id=DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>2,
            'root_id'=>2,
            'title'=>$faker->text,
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>$faker->text,
            'admin_user_id'=>1,
        ]);
        $id=DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>0,
            'root_id'=>5,
            'title'=>$faker->text,
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>$faker->text,
            'admin_user_id'=>1,
        ]);
        $id=DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>5,
            'root_id'=>5,
            'title'=>$faker->text,
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>$faker->text,
            'admin_user_id'=>1,
        ]);
        DB::table('inquiries')->insertGetId([
            'department_id'=>1,
            'parent_id'=>6,
            'root_id'=>6,
            'title'=>$faker->text,
            'email'=>$faker->email,
            'phone'=>$faker->phoneNumber,
            'content'=>$faker->text,
            'admin_user_id'=>1,
        ]);

        
    }
}
