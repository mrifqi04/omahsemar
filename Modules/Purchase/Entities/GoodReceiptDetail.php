<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Product;

class GoodReceiptDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'good_receipt_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'qty_po',
        'qty_gr',
        'note',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
