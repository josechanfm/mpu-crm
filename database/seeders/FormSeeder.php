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
            'organization_id' => '2',
            'name'=>'first form',
            'title'=>'First form of title'
        ]);
        DB::table('form_fields')->insert([
            'form_id' => '1',
            'field'=>'field 1',
            'label'=>'label 1'
        ]);

    }
}
