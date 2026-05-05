<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Souvenir extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','thumbnail','stock','price','quota','available', 'available_to','is_available','thumbnail','images','remark'];
    protected $casts = ['images'=>'array','is_available'=>'boolean','status'=>'boolean',    'available_to' => 'datetime'];
    protected $appends = ['user_quota_remaining'];

    public function getUserQuotaRemainingAttribute()
    {
        // Get current user from session
        $souvenirUser = session("souvenirUser");
        if (!$souvenirUser) {
            return $this->quota; // no user logged in, full quota available
        }

        $userId = $souvenirUser->id;

        // Sum quantities already purchased for this souvenir by this user
        $boughtQty = SouvenirOrder::where('souvenir_user_id', $userId)
            ->where('status', true)
            ->get()
            ->sum(function ($order) {
                $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                foreach ($items as $item) {
                    if (isset($item['souvenir_id']) && $item['souvenir_id'] == $this->id) {
                        return $item['qty'];
                    }
                }
                return 0;
            });
        
        $remaining = $this->quota - $boughtQty;
        return max($remaining, 0);
    }

    // Optional helper method
    public function getRemainingQuotaForUser($userId = null)
    {
        if (!$userId) {
            $souvenirUser = session("souvenirUser");
            if (!$souvenirUser) return $this->quota;
            $userId = $souvenirUser->id;
        }
        // Reuse the same logic or call the accessor
        return $this->user_quota_remaining;
    }
    public static function checkAvailable($user, $cart){
        $failedItems = [];
        foreach ($cart['cartItems'] as $item) {
            $souvenir = Souvenir::find($item['id']);
            
            if (!$souvenir) {
                $failedItems[] = ['name' => $item['name'] ?? 'Unknown', 'reason' => 'Product not found'];
                continue;
            }
            
            if (!$souvenir->is_available) {
                $failedItems[] = ['name' => $souvenir->name, 'reason' => 'Not available for order'];
                continue;
            }
            if ($item['qty'] > $souvenir->user_quota_remaining) {
                $failedItems[] = [
                    'id'=>$souvenir->id,
                    'name' => $souvenir->name,
                    'available' => $souvenir->quota,
                    'requested' => $item['qty'],
                    'error_code'=> '10',
                    'reason' => 'Insufficient quota',
                ];
                continue;
            }
            if ($item['qty'] > $souvenir->available) {
                $failedItems[] = [
                    'id'=>$souvenir->id,
                    'name' => $souvenir->name,
                    'available' => $souvenir->quota,
                    'requested' => $item['qty'],
                    'error_code'=>'20',
                    'reason' => 'Insufficient quota',
                ];
                continue;
            }
        }
        if (!empty($failedItems)) {
            return ['success' => false, 'failedItems' => $failedItems];
        }
        return ['success'=>true, 'failtedItems'=>[]];
    }

    public static function createOrder($user, $cart){
        $result=self::checkAvailable($user, $cart);
        if(!$result['success']){
            return $result;
        }
        $orderItems=[];
        $totalAmount=0;
        foreach($cart['cartItems'] as $item){
            $souvenir=Souvenir::find($item['id']);
            // $souvenir->update(['available' => $souvenir->available - $item['qty']]);
            // $souvenir->save();
            $orderItems[]=[
                'souvenir_id'=> $souvenir->id,
                'name'=> $souvenir->name,
                'qty'=>$item['qty'],
                'price'=>$souvenir->price,
                'amount'=>$souvenir->price*$item['qty'],
            ];
            $totalAmount+=$souvenir->price*$item['qty'];
        }
        try {
            $order=$souvenirUser->orders()->firstOrCreate([
                'uuid'=>Str::uuid(),
                'souvenir_user_id'=>$souvenirUser->id,
                'form_meta'=>$cart,
                'items'=>$orderItems,
                'currency'=>'MOP',
                'amount'=>$totalAmount,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
        return ['success' => true, 'order' => $order, 'failedItems'=>[]];

    }
    public static function deductAvailable($user, $cart){

    }

}