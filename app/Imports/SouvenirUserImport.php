<?php

namespace App\Imports;

use App\Models\SouvenirUser;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SouvenirUserImport implements ToModel,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow():int{
        return 2;
    }
    public function model(array $row)
    {
        return new SouvenirUser([
            'netid'=>$row[0],
            'name_zh'=>$row[1],
            'name_en'=>$row[2],
            'email'=>$row[3],
            'phone'=>$row[4],
            'faculty_code'=>$row[5],
            'degree_code'=>$row[6],
            'can_buy'=>$row[7],
        ]);
    }
}
