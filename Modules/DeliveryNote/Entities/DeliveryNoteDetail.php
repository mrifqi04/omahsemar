<?php

namespace Modules\DeliveryNote\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Product;

class DeliveryNoteDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['deliveryNote'];

    public function deliveryNote() {
        return $this->belongsTo(DeliveryNote::class, 'delivery_notes_id', 'id');
    }
}