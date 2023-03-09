<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        for($i=1;$i<=10;$i++){
            DB::table('rooms')->insert([
                'code'=>'A'.substr('00'.$i,-3),
                'name'=>'教室',
                'type'=>'REGULAR',
                'seat'=>20,
            ]);
        }
    }
}
