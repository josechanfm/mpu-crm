<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffRelative extends Model
{
    use HasFactory;
    protected $fillable=[
        'staff_id',
        'has_allowance',
        'has_medical',
        'medical_num',
        'staff_num',
        'name_zh',
        'name_pt',
        'relationship',
        'allowance_type',
        'dob',
        'id_num',
        'medical_type',
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
