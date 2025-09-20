<?php

namespace Modules\Stockout\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stockout extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_note_id',
        'date',
        'reference',
        'note',
        'total'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = Stockout::max('id') + 1;
            $model->reference = make_reference_id('SO', $number);
        });
    }

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class, 'delivery_note_id', 'id');
    }

    public function stockOutDetails()
    {
        return $this->hasMany(StockOutDetails::class, 'stockout_id', 'id');
    }
}
