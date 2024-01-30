<?php

namespace App\Imports;

use App\Models\Gpdp;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GpdpImport implements ToModel,WithStartRow
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
        return new Gpdp([
            'staff_num'=>$row[0],
            'name_zh'=>$row[1],
            'name_fr'=>$row[2],
            'id_num'=>$row[3],
            'current_department'=>$row[4],
            'current_position'=>$row[5],
            'original_department'=>$row[6],
            'original_position'=>$row[7],
            'employment_type'=>$row[8],
            'date_start'=>$row[9],
            'date_remind'=>$row[10],
            'date_due'=>$row[11],
            'email'=>$row[12],
            'is_valid'=>$row[13],
            'remark'=>$row[14],
        ]);
    }
}
