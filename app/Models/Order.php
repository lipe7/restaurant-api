<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'table',
        'status'
    ];

    public function itemsOrders()
    {
        return $this->hasMany(ItemOrder::class);
    }
}
