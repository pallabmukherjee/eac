<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PensionerOtherBillSummary extends Model
{
    protected $table = 'pensioner_other_bill_summaries';

    protected $fillable = [
        'bill_id',
        'pensioner_id',
        'amount',
    ];

    public function pensionerDetails() {
        return $this->belongsTo(Pensioner::class, 'pensioner_id');
    }
}
