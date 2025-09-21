<?php

namespace Modules\StockOut\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\DeliveryNote\Entities\DeliveryNote;
use Modules\StockOut\Entities\StockoutDetail;

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
        // boot
        static::creating(function ($model) {
            $number = StockOut::max('id') + 1;
            $model->reference = make_reference_id('SO', $number);
        });
    }

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class, 'delivery_note_id', 'id');
    }

    public function stockOutDetails()
    {
        return $this->hasMany(StockoutDetails::class, 'stockout_id', 'id');
    }
}
