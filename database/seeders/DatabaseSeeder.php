<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roleMaster=Role::create(['name'=>'master','guard_name' => 'admin_web']);
        $roleAdmin=Role::create(['name'=>'admin','guard_name' => 'admin_web']);
        $roleDepartment=Role::create(['name'=>'department','guard_name' => 'admin_web']);
        $rolePES=Role::create(['name'=>'PES','guard_name' => 'admin_web']);
        $rolePES=Role::create(['name'=>'DAMIA','guard_name' => 'admin_web']);

        $roleMember=Role::create(['name'=>'member','guard_name' => 'web']);
        

        $admin=\App\Models\AdminUser::factory([
            'username'=>'master',
            'name' => 'Master',
            'email' => 'master@example.com',
            'password'=> Hash::make('password')
        ])->create();

        //$admin->assignRole('master');

        $admin=\App\Models\AdminUser::factory([
            'username'=>'admin',
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password'=> Hash::make('password')
        ])->create();

        $member=\App\Models\User::factory([
            'name' => 'Member',
            'email' => 'member@example.com',
            'password'=> Hash::make('password')
        ])->withPersonalTeam()->create();

        //$member->assignRole('member');
        $adminUser=\App\Models\AdminUser::where('username','admin')->first();
        $role=Role::where('name','admin')->first();
        $adminUser->roles()->attach($role);
        $role=Role::where('name','PES')->first();
        $adminUser->roles()->attach($role);


        $this->call([
            ConfigSeeder::class,
            MemberSeeder::class,
            DepartmentSeeder::class,
            FormSeeder::class,
            FaqSeeder::class,
            EnquirySeeder::class,

            //UserSeeder::class,
            //EmailSeeder::class,
        ]);


    }
}
