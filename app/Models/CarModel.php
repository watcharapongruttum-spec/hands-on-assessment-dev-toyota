<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarModel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'brand',
        'name',
        'year',
        'body_type',
        'base_price',
        'is_active',
        'deletion_reason',
        'deletion_detail',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
    ];
}