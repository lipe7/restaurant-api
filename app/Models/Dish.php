<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price'
    ];

    public function itemsOrders()
    {
        return $this->hasMany(ItemOrder::class, 'item_id');
    }
}
