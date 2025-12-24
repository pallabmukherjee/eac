<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PensionerOtherBill extends Model
{
    protected $table = 'pensioner_other_bills';

    protected $fillable = [
        'bill_id',
        'details',
        'total_amount',
    ];
}
