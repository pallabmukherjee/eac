<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveIncrement extends Model
{
    protected $table = 'leave_increment';

    protected $fillable = [
        'month',
        'type',
    ];
}
