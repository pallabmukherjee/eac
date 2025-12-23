<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GratuityBill extends Model
{
    protected $table = 'gratuity_bills';

    protected $fillable = [
        'bill_id',
        'bill_no',
        'status',
    ];
}
