<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ItemLocation\Entities\ItemLocation;
use Modules\Product\Entities\Product;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'item_location_id',
        'stock_date',
        'stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function ItemLocation()
    {
        return $this->hasOne(ItemLocation::class, 'id', 'item_location_id');
    }
}
