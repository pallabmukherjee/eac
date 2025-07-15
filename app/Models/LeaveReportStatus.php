<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveReportStatus extends Model
{
    protected $table = 'leave_report_status';

    protected $fillable = [
        'report_month',
    ];
}
