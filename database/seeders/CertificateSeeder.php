<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('certificates')->insert([
            'name'=>'裁判資格認證',
            'cert_title'=>'裁判資格認證',
            'cert_body'=>'澳門柔道總會',
            'description'=>'澳門柔道總會裁判資格認證<br><ul><li>aaa</li></ul>'
        ]);
        DB::table('certificates')->insert([
            'name'=>'考官資格認證',
            'cert_title'=>'考官資格認證',
            'cert_body'=>'澳門柔道總會',
            'description'=>'澳門柔道總會裁判資格認證<br><ul><li>aaa</li></ul>'
        ]);
        DB::table('certificate_member')->insert([
            'certificate_id'=>'1',
            'member_id'=>'1',
            'display_name'=>'謝廣漢',
            'number'=>'1',
            'issue_date'=>'2023-01-01',
            'valid_from'=>'2023-01-01',
            'valid_until'=>'2024-12-31',
            'authorize_by'=>'謝廣漢',
            'rank'=>'1',
        ]);
        DB::table('certificate_member')->insert([
            'certificate_id'=>'2',
            'member_id'=>'1',
            'display_name'=>'謝廣漢',
            'number'=>'2',
            'issue_date'=>'2023-02-01',
            'valid_from'=>'2023-02-01',
            'valid_until'=>'2025-12-31',
            'authorize_by'=>'謝廣漢',
            'rank'=>'1',
        ]);

    }
}
