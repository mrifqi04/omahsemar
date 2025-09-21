<?php

namespace Modules\DeliveryNote\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Stockout\Entities\Stockout;


class DeliveryNote extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = DeliveryNote::max('id') + 1;
            $model->reference = make_reference_id('DN', $number);
        });
    }

    public function deliveryNoteDetails()
    {
        return $this->hasMany(DeliveryNoteDetail::class, 'delivery_note_id', 'id');
    }

     public function stockout()
    {
        return $this->hasOne(Stockout::class, 'delivery_note_id', 'id');
    }
}