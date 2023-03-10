<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    protected $appends=['cid','cert_number'];

    public function getCidAttribute(){
        return substr('000'.strtoupper(base_convert($this->pivot->id,10,36)),-3);
    }

    public function getCertNumberAttribute(){
        return preg_replace('/0+/i',$this->number,$this->cert_number_format);
        //return '00000'.$this->pivot->number;
    }

    public function members(){
        return $this->belongsToMany(Member::class);
    }
}
