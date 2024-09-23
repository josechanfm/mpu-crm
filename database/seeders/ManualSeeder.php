<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Manual;

class ManualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manual::create([
            'parent_id'=>0,
            'route'=>'default',
            'title'=>'Manual Menu Index Page',
            'content'=>'Manual Menu Index Page'
        ]);
        Manual::create([
            'parent_id'=>0,
            'route'=>'tutorial',
            'title'=>'Tutorial Index Page',
            'content'=>'Tutorial home page'
        ]);
        Manual::create([
            'parent_id'=>0,
            'route'=>'faq',
            'title'=>'Faq Index Page',
            'content'=>'Faq home page'
        ]);
    }
}
