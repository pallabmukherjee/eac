<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveReport extends Model
{
    protected $table = 'leave_reports';

    protected $fillable = [
        'emp_id',
        'emp_name',
        'emp_designation',
        'month',
        'cl',
        'cl_enjoyed',
        'cl_in_hand',
        'cl_date',
        'el',
        'el_enjoyed',
        'el_in_hand',
        'el_date',
        'ml',
        'ml_enjoyed',
        'ml_in_hand',
        'ml_date',
    ];

    public function employeeType() {
        return $this->belongsTo(EmployeeType::class, 'emp_designation'); // Assuming emp_designation is the foreign key
    }


}
