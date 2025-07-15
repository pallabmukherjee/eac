<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GratuityLoanPay extends Model
{
    protected $table = 'gratuity_loan_pay';

    protected $fillable = [
        'bill_id',
        'bill_no',
        'emp_id',
        'bank',
        'amount',
    ];
}
