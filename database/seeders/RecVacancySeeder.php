<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\RecVacancy;

class RecVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rec_vacancies')->truncate();
        DB::table('rec_notices')->truncate();

        $data=[
            ['type'=>'ACA','code'=>'ACA-0001-2025',
            'title_zh'=>'ACA Recruitment (zh)','title_en'=>'ACA Recruitment (en)','title_pt'=>'ACA Recruitment (pt)',
            'date_start'=>'2025-01-01','date_end'=>'2026-01-01','date_publish'=>'2025-01-02',
            ],
            ['type'=>'ADM','code'=>'ADM-1111-2025',
            'title_zh'=>'ADM Recruitment (zh)','title_en'=>'ACA Recruitment (en)','title_pt'=>'ACA Recruitment (pt)',
            'date_start'=>'2025-02-01','date_end'=>'2026-02-01','date_publish'=>'2025-02-02',
            ],
        ];
        foreach($data as $d){
            $vacancy=RecVacancy::create($d);
            $vacancy->notices()->create([
                'title_zh'=>'開考通告',
                'title_zh'=>'Recruitment Notice',
                'title_zh'=>'Aviso de Recrutamento',
                'date_start'=>'2025-02-05',
                'date_end'=>'2025-02-28 15:15:0',
            ]);
        }
    }
}
