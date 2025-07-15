<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GratuityBillSummary extends Model
{
    protected $table = 'gratuity_bill_summary';

    protected $fillable = [
        'bill_id',
        'bill_no',
        'emp_id',
        'voucher_number',
        'voucher_date',
        'id_number',
        'reference',
        'gratuity_amount',
        'loan_amount'
    ];

    public function empDetails() {
        return $this->belongsTo(Gratuity::class, 'emp_id');
    }
}
