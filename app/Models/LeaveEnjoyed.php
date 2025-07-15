<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveEnjoyed extends Model
{
    protected $table = 'leave_enjoyeds';

    protected $fillable = [
        'emp_id',
        'leave_type',
        'leave_date',
    ];
}
