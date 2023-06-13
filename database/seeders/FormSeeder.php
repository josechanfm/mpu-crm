<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('forms')->insert([
            'department_id'=>1,
            'name'=>'first form',
            'title'=>'First form of title'
        ]);
        DB::table('form_fields')->insert([
            'form_id' => '1',
            'field_name'=>'username',
            'field_label'=>'Username',
            'type'=>'input'
        ]);
        DB::table('form_fields')->insert([
            'form_id' => '1',
            'field_name'=>'gender',
            'field_label'=>'Gender',
            'type'=>'radio',
            'options'=>'[{"value":"M","label":"Male"},{"value":"F","label":"Female"}]'
        ]);
        DB::table('form_fields')->insert([
            'form_id' => '1',
            'field_name'=>'dob',
            'field_label'=>'DOB',
            'type'=>'date',
        ]);
        DB::table('form_fields')->insert([
            'form_id' => '1',
            'field_name'=>'education',
            'field_label'=>'Education',
            'type'=>'select',
            'options'=>'[{"value":"B","label":"Bachalor"},{"value":"M","label":"Master"},{"value":"D","label":"PhD."}]'
        ]);
        DB::table('form_fields')->insert([
            'form_id' => '1',
            'field_name'=>'email',
            'field_label'=>'Email',
            'type'=>'email'
        ]);
        DB::table('form_fields')->insert([
            'form_id' => '1',
            'field_name'=>'remark',
            'field_label'=>'Remark',
            'type'=>'textarea'
        ]);


        DB::table('forms')->insert([
            'department_id'=>1,
            'name'=>'second form',
            'title'=>'Second form of title',
            'published'=>true
        ]);
        DB::table('form_fields')->insert([
            'form_id' => '2',
            'field_name'=>'username',
            'field_label'=>'Username',
            'type'=>'input',
        ]);


    }
}
