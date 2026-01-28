<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffNotice extends Model
{
    use HasFactory;
    protected $fillable=['email','age','title','body','date','sent_at','status'];

    public function relative(){
        return $this->belongsTo(StaffRelative::class,'staff_relative_id');
    }

    public function staff()
    {
        return $this->hasOneThrough(
            Staff::class, // The target model
            StaffRelative::class, // The intermediate model
            'id', // Foreign key on StaffRelation table (this should be the staff_relation_id in StaffNotice)
            'id', // Foreign key on Staff table
            'staff_relative_id', // Local key on StaffNotice (this should match 'relation_id')
            'staff_id' // Local key on StaffRelation (this should match the foreign key used in StaffRelation to link to Staff)
        );
    }
}
