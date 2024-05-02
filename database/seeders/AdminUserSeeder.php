<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->insert([
            'username' => 'Master',
            'name' => 'master',
            'email' => 'master@example.com',
            'email_varified_at' => null,
            'password'=> Hash::make('password'),
            'remember_token'=>null,
            'current_team_id'=>null,
            'profile_photo_path'=>null,
            'guid'=>null,
            'domain'=>null
        ]);
        DB::table('admin_users')->insert([
            'username' => 'Admin',
            'name' => 'admin',
            'email' => 'admin@example.com',
            'email_varified_at' => null,
            'password'=> Hash::make('password'),
            'remember_token'=>null,
            'current_team_id'=>null,
            'profile_photo_path'=>null,
            'guid'=>null,
            'domain'=>null
        ]);
    }
}
