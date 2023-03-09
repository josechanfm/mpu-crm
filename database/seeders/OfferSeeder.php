<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $course=Course::find(1);
        DB::table('offers')->insert([
            'course_id'=>'1',
            'subset'=>'01',
            'title_zh'=>$course->title_zh,
            'apply_start'=>date('Y-m-d', strtotime('2 week')),
            'apply_end'=> date('Y-m-d', strtotime('1 days')),
            'course_start'=> date('Y-m-d', strtotime('+8 days')),
            'course_end'=> date('Y-m-d', strtotime('4 week')),
            'seat'=>$course->quota,
            'price'=>$course->price,
            'description'=>$course->description,
            'publish'=>true,
        ]);
        $course=Course::find(2);
        DB::table('offers')->insert([
            'course_id'=>'1',
            'subset'=>'01',
            'title_zh'=>$course->title_zh,
            'apply_start'=>date('Y-m-d', strtotime('2 week')),
            'apply_end'=> date('Y-m-d', strtotime('1 days')),
            'course_start'=> date('Y-m-d', strtotime('+8 days')),
            'course_end'=> date('Y-m-d', strtotime('4 week')),
            'seat'=>$course->quota,
            'price'=>$course->price,
            'description'=>$course->description,
            'publish'=>true,
        ]);
        $course=Course::find(3);
        DB::table('offers')->insert([
            'course_id'=>'1',
            'subset'=>'01',
            'title_zh'=>$course->title_zh,
            'apply_start'=>date('Y-m-d', strtotime('2 week')),
            'apply_end'=> date('Y-m-d', strtotime('1 days')),
            'course_start'=> date('Y-m-d', strtotime('+8 days')),
            'course_end'=> date('Y-m-d', strtotime('4 week')),
            'seat'=>$course->quota,
            'price'=>$course->price,
            'description'=>$course->description,
            'publish'=>true,
        ]);

    }
}
