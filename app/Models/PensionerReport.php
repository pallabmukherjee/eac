<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PensionerReport extends Model
{
    protected $table = 'pensioner_reports';

    protected $fillable = [
        'report_id',
        'month',
        'year'
    ];

}
