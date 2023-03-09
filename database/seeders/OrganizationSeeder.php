<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::factory()->count(10)->create();
        DB::table('admin_user_organization')->insert([
            'admin_user_id'=>'2',
            'organization_id'=>'1',
        ]);
        DB::table('admin_user_organization')->insert([
            'admin_user_id'=>'2',
            'organization_id'=>'3',
        ]);
        DB::table('admin_user_organization')->insert([
            'admin_user_id'=>'3',
            'organization_id'=>'2',
        ]);



        DB::table('member_organization')->insert([
            'member_id'=>'1',
            'organization_id'=>'1',
        ]);
        DB::table('member_organization')->insert([
            'member_id'=>'2',
            'organization_id'=>'1',
        ]);
        DB::table('member_organization')->insert([
            'member_id'=>'3',
            'organization_id'=>'1',
        ]);


        DB::table('member_organization')->insert([
            'member_id'=>'4',
            'organization_id'=>'2',
        ]);
        DB::table('member_organization')->insert([
            'member_id'=>'5',
            'organization_id'=>'2',
        ]);
        DB::table('member_organization')->insert([
            'member_id'=>'6',
            'organization_id'=>'2',
        ]);




    }
}
