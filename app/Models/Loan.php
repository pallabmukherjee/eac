<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loans';

    protected $fillable = [
        'emp_code',
        'bank_name',
        'loan_amount',
        'loan_details',
    ];

    public function emp() {
        return $this->belongsTo(Gratuity::class, 'emp_code');
    }
}
