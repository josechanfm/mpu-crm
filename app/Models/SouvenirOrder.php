<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SouvenirOrder extends Model
{
    use HasFactory;
    
    protected $fillable = ['uuid','merc_order_no','sourvenir_user_id','form_meta','items','currency','amount','payment_method','payment_status','status'];
    protected $casts = ['form_meta'=>'object','items'=>'array'];
    protected $appends=['receipt_no'];
    
    public function getReceiptNoAttribute(){
       // Get the year from created_at
        $year = $this->created_at->format('Y');

        // Pad the ID with leading zeros to ensure it's 6 digits
        $idPadded = str_pad($this->id, 6, '0', STR_PAD_LEFT);

        // Concatenate the year and padded ID
        return $year .'-'. $idPadded;
        
    }
    public function user(){
        return $this->belongsTo(SouvenirUser::class,'souvenir_user_id','id');
    }
    public function payment(){
        return $this->belongsTo(SouvenirPayment::class);
    }
}
