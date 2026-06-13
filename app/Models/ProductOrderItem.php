<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrderItem extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(ProductOrder::class, 'product_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
