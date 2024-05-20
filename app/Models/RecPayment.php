<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecPayment extends Model
{
    use HasFactory;
    protected $fillable=[
        'rec_application_id',
        'system_code',  
        'ip_address',
        'cashier_language',
        'amount',
        'original_amount',
        'subject',
        'product_desc',
        'merc_order_no',
        'requester',
        'order_date',
        'order_time',
        'valid_number',
        'other_business_type',
        'payment_channel',
        'mcs_sync_order_no',
        'email',
        'sign_text'
    ];

    public function returns(){
        $this->hasMany(RecPaymentReturn::class);
    }
}
