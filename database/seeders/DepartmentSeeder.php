<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::factory()->count(10)->create();
        DB::table('admin_user_department')->insert([
            'admin_user_id'=>'2',
            'department_id'=>'1',
        ]);
        // DB::table('admin_user_department')->insert([
        //     'admin_user_id'=>'2',
        //     'department_id'=>'3',
        // ]);
        // DB::table('admin_user_department')->insert([
        //     'admin_user_id'=>'3',
        //     'department_id'=>'2',
        // ]);



        // DB::table('member_department')->insert([
        //     'member_id'=>'1',
        //     'department_id'=>'1',
        // ]);
        // DB::table('member_department')->insert([
        //     'member_id'=>'2',
        //     'department_id'=>'1',
        // ]);
        // DB::table('member_department')->insert([
        //     'member_id'=>'3',
        //     'department_id'=>'1',
        // ]);


        // DB::table('member_department')->insert([
        //     'member_id'=>'4',
        //     'department_id'=>'2',
        // ]);
        // DB::table('member_department')->insert([
        //     'member_id'=>'5',
        //     'department_id'=>'2',
        // ]);
        // DB::table('member_department')->insert([
        //     'member_id'=>'6',
        //     'department_id'=>'2',
        // ]);




    }
}
