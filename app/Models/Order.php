<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_method',
        'payment_proof',
        'rejection_reason',
        'snap_token',
        'recipient_name',
        'phone_number',
        'address',
        'latitude',
        'longitude',
        'distance_km',
        'shipping_cost',
        'shipping_service',
        'points_awarded',
        'tracking_link',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returnRequest(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ReturnRequest::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(OrderMessage::class);
    }

    public function review(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Review::class);
    }

    public static function autoCompleteOldOrders()
    {
        $shippingOrders = self::whereIn('status', ['Dikirim', 'Sedang Dikirim', 'Pesanan Sedang Dikirim'])->get();
        foreach ($shippingOrders as $o) {
            if ($o->updated_at->addDays(2)->isPast()) {
                $o->status = 'Selesai';
                if (!$o->points_awarded) {
                    $user = $o->user;
                    if ($user) {
                        $subtotal = $o->items->sum(fn($item) => $item->price * $item->quantity);
                        $pointsEarned = floor($subtotal / 10000);
                        $user->increment('points', $pointsEarned);
                        $o->points_awarded = true;
                    }
                }
                $o->save();
                Notification::send('pelanggan', 'Pesanan Otomatis Selesai', 'Pesanan #' . $o->order_number . ' telah diselesaikan oleh sistem secara otomatis.', $o->user_id, 'success', '/pelanggan/riwayat');
                Notification::send('admin', 'Pesanan Otomatis Selesai #' . $o->order_number, 'Pesanan telah otomatis diselesaikan sistem setelah 2 hari pengiriman.', null, 'success', '/admin/manajemen-pesanan');
            }
        }
    }
}
