<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Organization;
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
        // DB::table('users')->insert([
        //     'name' => 'Admin',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('password'),
        // ]);

        $this->call([
            MemberSeeder::class,
            OrganizationSeeder::class,
            FormSeeder::class
        ]);


        $roleMaster=Role::create(['name'=>'master','guard_name' => 'admin_web']);
        $roleAdmin=Role::create(['name'=>'admin','guard_name' => 'admin_web']);
        $roleOrganization=Role::create(['name'=>'organization','guard_name' => 'admin_web']);
        $roleDeportment=Role::create(['name'=>'department','guard_name' => 'admin_web']);
        $roleMember=Role::create(['name'=>'member','guard_name' => 'web']);

        // $permissionCourse=Permission::create(['name'=>'manage course','guard_name' => 'admin_web']);
        // $permissionOffer=Permission::create(['name'=>'manage offer','guard_name' => 'admin_web']);
        // $permissionTeacher=Permission::create(['name'=>'manage teacher','guard_name' => 'admin_web']);
        // $permissionStudent=Permission::create(['name'=>'manage student','guard_name' => 'admin_web']);
        // $permissionGuardian=Permission::create(['name'=>'manage guardian','guard_name' => 'admin_web']);
        // $permissionAttendance=Permission::create(['name'=>'manage attendance','guard_name' => 'admin_web']);

        // $roleMaster->givePermissionTo($permissionCourse);
        // $roleMaster->givePermissionTo($permissionOffer);
        // $roleMaster->givePermissionTo($permissionTeacher);
        // $roleMaster->givePermissionTo($permissionStudent);
        // $roleMaster->givePermissionTo($permissionGuardian);
        // $roleMaster->givePermissionTo($permissionAttendance);

        // $roleAdmin->givePermissionTo($permissionOffer);
        // $roleAdmin->givePermissionTo($permissionTeacher);
        // $roleAdmin->givePermissionTo($permissionStudent);
        // $roleAdmin->givePermissionTo($permissionGuardian);
        // $roleAdmin->givePermissionTo($permissionAttendance);

        // $roleOrganization->givePermissionTo($permissionStudent);
        // $roleOrganization->givePermissionTo($permissionAttendance);

        $admin=\App\Models\AdminUser::factory([
            'name' => 'Master',
            'email' => 'master@example.com',
            'password'=> Hash::make('password')
        ])->create();
        $admin->assignRole('master');

        $admin=\App\Models\AdminUser::factory([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password'=> Hash::make('password')
        ])->create();
        $admin->assignRole('admin');
        $admin->assignRole('organization');
        $admin->assignRole('department');

        $organization=\App\Models\AdminUser::factory([
            'name' => 'Organization',
            'email' => 'organization@example.com',
            'password'=> Hash::make('password')
        ])->create();
        $organization->assignRole('organization');

        $member=\App\Models\User::factory([
            'name' => 'Member',
            'email' => 'member@example.com',
            'password'=> Hash::make('password')
        ])->withPersonalTeam()->create();
        $member->assignRole('member');

    }
}
