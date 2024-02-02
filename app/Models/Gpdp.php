<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Gpdp extends Model
{
    use HasFactory;
    protected $fillable=['staff_num','name_zh','name_fr','id_num','current_department','current_position','original_department','original_position','date_start','date_remind','date_due','email','is_valid','remark'];
    protected $appends=['sent_email_count'];

    public function getSentEmailCountAttribute(){
        return $this->emails->count();
    }
    public function emails(): MorphMany{
        return $this->morphMany(Email::class, 'emailable');
    }    
}
