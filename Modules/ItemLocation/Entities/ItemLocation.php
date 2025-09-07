<?php

namespace Modules\ItemLocation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemLocation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'location_name',
    ];
}
