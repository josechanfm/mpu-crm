<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Souvenir extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','thumbnail','stock','price','quota','available', 'available_to','is_available','thumbnail','images','remark'];
    protected $casts = ['images'=>'array','is_available'=>'boolean','status'=>'boolean'];
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
}