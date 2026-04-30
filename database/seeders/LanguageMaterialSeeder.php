<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LanguageMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get the path to the JSON file
        //en-US, pt-PT, de-DE
        $lang='de-DE';
        $jsonPath = database_path('data/language_materials_'.$lang.'.json');
        
        // 2. Check if file exists to avoid errors
        if (!File::exists($jsonPath)) {
            $this->command->error("JSON file not found at: {$jsonPath}");
            return;
        }

        // 3. Read and decode the JSON
        $json = File::get($jsonPath);
        $materials = json_decode($json, true);

        // 4. Insert into database
        foreach ($materials as $material) {
            DB::table('language_materials')->insert([
                'lang'        => $lang,
                'title'       => $material['title'],
                'level'       => $material['level'],
                'content'     => $material['content'],
                'translation' => $material['translation'],
                'type'        => $material['type'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $this->command->info('Language materials seeded successfully from JSON!');
    }
}