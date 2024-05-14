<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecUpload extends Model
{
    use HasFactory;
    protected $fillable=['rec_application_id','document_type','file_name','path','full_path','original_name'];

    public function application(){
        return $this->belongsTo(RecApplication::class);
    }
}
