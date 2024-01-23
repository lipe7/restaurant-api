<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    use HasFactory;

    protected $table = 'items_orders';

    protected $fillable = [
        'order_id',
        'item_id',
        'quantity'
    ];

    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'item_id', 'id');
    }
}
