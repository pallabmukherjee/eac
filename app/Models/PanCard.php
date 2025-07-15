<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanCard extends Model
{
    protected $table = 'pan_card';

    protected $fillable = [
        'code',
        'label',
        'tax',
    ];

}
