<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'coupon_type',
        'user_limit',
        'coupon_code',
        'start_date',
        'expire_date',
        'min_purchase',
        'max_discount',
        'discount',
        'discount_type',
        'status'
    ];
}
