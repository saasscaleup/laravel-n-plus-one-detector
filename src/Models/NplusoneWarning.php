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


}