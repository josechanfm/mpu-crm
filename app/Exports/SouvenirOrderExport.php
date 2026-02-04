<?php

namespace App\Exports;

use App\Models\SouvenirOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SouvenirOrderExport implements FromCollection, WithHeadings
{
    protected $orderIds;
    protected $heading;
    protected $modifiedOrders;

    public function __construct($orderIds = [])
    {
        $this->modifiedOrders = collect();
        $this->orderIds = $orderIds;
        $this->heading=[
            'NetId',
            'Name (en)',
            'Receipt Num',
            'Payment Method',
            'Payment Amount',
            'Status',
            'Redemption',
            'Transaction Time Stamp',
            'Purchase Year',
            'Purchase Month',
            'Email',
            'Phone',
            'Faculty',
            'Degree',
            'Grad Year',
            'item_1_name',
            'item_1_count',
            'item_2_name',
            'item_2_count',
        ];
    }

    public function collection()
    {
        // dd($this->orderIds, empty($this->orderIds));
        if (empty($this->orderIds)) { 
            $souvenirOrders = SouvenirOrder::with('user')->get();
        } else {
            $souvenirOrders = SouvenirOrder::with('user')->whereIn('id', $this->orderIds)->get();
        }



        foreach ($souvenirOrders as $order) {
            //dd($order->form_meta->cartItems);
            $modifiedOrder = [
                'netid'=>$order->user->netid,
                'name_en'=>$order->user->name_en,
                'receipt_num'=>$order->receipt_no,
                'payment_ethod'=>'online',
                'payment_amount'=>$order->amount,
                'status'=>$order->status>0?'PAID':'UNPAID',
                'redemption'=>$order->status==2?'已領':'未領取',
                'transaction_time'=>$order->updated_at,
                'purchase_year'=>$order->updated_at->format('Y'),
                'purchase_month'=>$order->updated_at->format('m'),
                'email'=>$order->form_meta->email,
                'phone'=>$order->form_meta->phone,
                'faculty'=>$order->form_meta->faculty,
                'degree'=>$order->form_meta->degree,
                'grad_Year'=>$order->updated_at->format('Y'),

            ];
            foreach($order->form_meta->cartItems as $item){
                if(isset($item->qty)){
                    $modifiedOrder['item_name_'.$item->id]=$item->name;
                    $modifiedOrder['item_'.$item->id]=$item->qty;
                }
                
                //$this->heading[]='item_'.$item->id;
            }
            $this->modifiedOrders->push($modifiedOrder);
        }
        //dd($this->modifiedOrders);
        // Return the modified collection
        return $this->modifiedOrders;
    }

    public function headings(): array
    {
        return $this->heading;
    }
}