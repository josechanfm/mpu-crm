<?php

namespace App\Exports;

use App\Models\SouvenirUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SouvenirUserExport implements FromCollection, WithHeadings
{
    protected $userIds;

    public function __construct($userIds = [])
    {
        $this->userIds = $userIds;
    }

    public function collection()
    {
        if (empty($this->userIds)) { 
            // dd(SouvenirUser::all());
            return SouvenirUser::all();
        }
        $data = [];
        $souvenirUsers = SouvenirUser::whereIn('id', $this->userIds)->get();

        foreach ($souvenirUsers as $user) {
            foreach ($user->orders()->get() as $order) {
                // Combine user and order data
                $o=$order->toArray();
                $o['form_meta']=json_encode($o['form_meta'], JSON_UNESCAPED_UNICODE);
                $o['items']=json_encode($o['items'], JSON_UNESCAPED_UNICODE);
                $o=array_merge(['order_id'=>$o['id']], $o);
                unset($o['id']);

                $data[] = array_merge($user->toArray(), $o);
            }
        }
        //dd(collect($data));
        return collect($data); // Return a collection
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student No',
            'Name (zh)',
            'Name (en)',
            'Email',
            'Phone',
            'Faculty',
            'Degree',
            'Can Buy',
            'Remark',
            'Created At',
            'Updated At',
            "order_id",
            "uuid",
            "merc_order_no",
            "souvenir_user_id",
            "form_meta",
            "items",
            "currency",
            "amount",
            "payment_method",
            "payment_status",
            "status",
        ];
    }
}