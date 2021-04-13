<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total_price',
        'items',
        'active',
        'customer_id'
    ];

    /**
     * Get the cart details for this user.
     */
    public function currentCart()
    {
        return $this->belongsTo(Users::class, 'customer_id');
    }
}
