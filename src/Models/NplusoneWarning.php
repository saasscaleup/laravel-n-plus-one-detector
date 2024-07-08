<?php

namespace Saasscaleup\NPlusOneDetector\models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NplusoneWarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'sql', 
        'location',
    ];

    /**
     * Create a new record in the database with the given attributes.
     *
     * @param array $attributes The attributes to create the record with.
     * @return \Illuminate\Database\Eloquent\Model The created model instance.
     */
    public static function createRecord(array $attributes = [])
    {
        return static::query()->create($attributes);
    }
}