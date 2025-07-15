<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PensionerReportSummary extends Model
{
    protected $table = 'pensioner_report_summary';

    protected $fillable = [
        'pensioner_id',
        'report_id',
        'gross',
        'arrear',
        'overdrawn',
        'net_pension',
        'remarks',
    ];

    public function pensionerDetails() {
        return $this->belongsTo(Pensioner::class, 'pensioner_id');
    }
}
