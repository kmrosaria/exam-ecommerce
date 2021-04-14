<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'items',
        'customer_id',
        'total_price',
        'total_quantity',
        'number',
        'address',
        'buyer',
        'payment_method'
    ];

    /**
     * Get the cart details for this user.
     */
    public function order()
    {
        return $this->belongsTo(Users::class, 'customer_id');
    }
}
