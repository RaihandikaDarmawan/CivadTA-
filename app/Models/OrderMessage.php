<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMessage extends Model
{
    protected $fillable = [
        'order_id',
        'sender_type',
        'sender_id',
        'message',
        'image',
        'is_read',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getSenderAttribute()
    {
        if ($this->sender_type === 'pelanggan') {
            return User::find($this->sender_id);
        } elseif ($this->sender_type === 'admin') {
            return Admin::find($this->sender_id);
        }
        return null;
    }
}
