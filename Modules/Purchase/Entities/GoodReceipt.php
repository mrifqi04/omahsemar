<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoodReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'date',
        'reference',
        'note',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = GoodReceipt::max('id') + 1;
            $model->reference = make_reference_id('GR', $number);
        });
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function goodReceiptDetails()
    {
        return $this->hasMany(GoodReceiptDetail::class, 'good_receipt_id', 'id');
    }
}
