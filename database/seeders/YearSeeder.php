<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yearId=DB::table('years')->order_by('id','desc')->first()->id;
        for($y=2021;$y<=2023;$y++){
            DB::table('years')->insert([
                'abbr'=>$y,
                'title'=>$y.'-'.($y+1),
                'start'=>$y.'-09-01',
                'end'=>($y+1).'-07-30',
                'active'=>1
            ]);
            $yearId = DB::getPdo()->lastInsertId();
            for($i=1;$i<=6;$i++){
                DB::table('grades')->insert([
                        'year_id'=>$yearId,
                        'level'=>$i,
                        'initial'=>'P'.$i,
                        'version'=>1,
                        'active'=>1
                ]);
            }
            for($i=7;$i<=12;$i++){
                DB::table('grades')->insert([
                        'year_id'=>$yearId,
                        'level'=>$i,
                        'initial'=>'S'.$i-6,
                        'version'=>1,
                        'active'=>1
                ]);
            }
        }
    }
}
