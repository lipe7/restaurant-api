<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const CANCELED = 0;
    const PENDING = 1;
    const IN_PROGRESS = 2;
    const FINISHED = 3;

    protected $table = 'orders';

    protected $fillable = [
        'client_name',
        'table',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(ItemOrder::class, 'order_id', 'id');
    }
}
