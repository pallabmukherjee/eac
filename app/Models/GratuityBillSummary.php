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
        'gratuity_amount',
        'loan_amount',
        'total_amount',
        'remarks',
        'prayer_no',
        'prayer_date',
        'voucher_no',
        'voucher_date',
        'id_no',
        'reference_no',
        'reference_date'
    ];

    public function empDetails() {
        return $this->belongsTo(Gratuity::class, 'emp_id');
    }
}
