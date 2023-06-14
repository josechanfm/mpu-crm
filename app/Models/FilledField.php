<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilledField extends Model
{
    use HasFactory;

    public function filled(){
        return $this->belongsTo(Filled::class);
    }
}
