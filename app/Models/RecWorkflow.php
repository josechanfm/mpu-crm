<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecWorkflow extends Model
{
    use HasFactory;
    protected $fillable=['vacancy_code','category_code','title_c','title_e','title_p','description','procedure_code','proposal_num','chariman','department_id','date_start','date_end','email_notice','handler','handler_email','status'];

    public function activities(){
        return $this->hasMany(RecActivity::class)->orderBy('sequence');
    }
    
}
