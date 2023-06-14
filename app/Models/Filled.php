<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Filled extends Model
{
    use HasFactory;
    
    protected $appends=['uid'];

    public function getUidAttribute(){
        $form=Form::find($this->form_id);
        return substr('00'.strtoupper(dechex($form->department_id)),-2).'-'.substr('0000'.strtoupper(dechex($this->id)),-5);
    }
    public function fields(){
        return $this->hasMany(FilledField::class);
    }
}
