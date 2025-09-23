<?php

namespace Modules\StockOut\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Product;

class StockoutDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stockout_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'qty_out',
        'qty_stockout',
        'note',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
