<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountShop extends Model
{
    protected $table = 'webg_dicount_shop';

    protected $fillable = [
        'id', 'max_discount_percentage', 'discount_step', 'countdown_duration', 'widget_text', 'countdown_ended_text', 'after_countdown_ends', 'discount_reactivation',
        'discount_reactivation_second', 'is_active', 'shop_id', 'applied_on_all', 'created_at', 'updated_at','total_seconds', 'discount_reactivation_days', 
        'discount_reactivation_hours', 'discount_reactivation_minutes', 
    ];
}
