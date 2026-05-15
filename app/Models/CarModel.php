<?php

namespace App\Models;

use App\Events\CarModelChanged;
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

    protected static function booted(): void
    {
        static::created(function (CarModel $model) {
            broadcast(new CarModelChanged(
                'created',
                $model->id
            ));
        });

        static::updated(function (CarModel $model) {
            broadcast(new CarModelChanged(
                'updated',
                $model->id
            ));
        });

        static::deleted(function (CarModel $model) {
            broadcast(new CarModelChanged(
                'deleted',
                $model->id
            ));
        });
    }
}