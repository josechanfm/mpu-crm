<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('users')->insert([
            'name' => 'Admin',
            'identity' => 'admin',
            'email' => 'admin@example.com',
            'password'=> Hash::make('password')
        ]);

        DB::table('users')->insert([
            'name' => 'David Ma',
            'identity' => 'student',
            'email' => 'david@example.com',
            'password'=> Hash::make('password')
        ]);
        
        DB::table('teams')->insert([
            'user_id' => '1',
            'name' => 'team',
            'personal_team' => '1',
        ]);
        DB::table('teams')->insert([
            'user_id' => '2',
            'name' => 'team',
            'personal_team' => '1',
        ]);

        DB::table('team_user')->insert([
            'team_id' => '1',
            'user_id' => '1',
        ]);

        DB::table('team_user')->insert([
            'team_id' => '1',
            'user_id' => '2',
        ]);

        
    }
}
