<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    protected $table = 'employee_leaves';

    protected $fillable = [
        'emp_number',
        'emp_name',
        'designation',
        'bill_no',
        'employee_id',
        'cl',
        'cl_enjoyed',
        'cl_in_hand',
        'el',
        'el_enjoyed',
        'el_in_hand',
        'ml',
        'ml_enjoyed',
        'ml_in_hand',
    ];

    public function employeeType() {
        return $this->belongsTo(EmployeeType::class, 'designation', 'id');
    }
}
