<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountShopProduct extends Model
{
    protected $table = 'webg_discount_shop_product';

    protected $fillable = [
        'id', 'shop_id', 'product_id',
        'created_at', 'updated_at',
    ];
}
