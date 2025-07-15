<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LifeCertificate extends Model
{
    protected $table = 'life_certificates';

    protected $fillable = [
        'user_id',
        'month',
        'amount',
        'status',
    ];
}
